<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUpdatePassword;
use SebastianBergmann\CodeUnit\FunctionUnit;
use App\Http\Requests\ConfirmTransferRequest;

class PageController extends Controller
{
    public function index() 
    {
        $user = Auth::user();
        return view('frontend.home', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('frontend.profile', compact('user'));
    }

    public function updatePassword()
    {
        return view('frontend.update_password');
    }

    public function updatePasswordStore(StoreUpdatePassword $request)
    {
        $user = Auth::user();

        if(!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['fail' => 'Check Your Current Password!'])->withInput();
        }

        if($request->new_password !== $request->password_confirmation) {
            return back()->withErrors(['not-match' => 'Password Confirmation doesn\'t match!'])->withInput();
        }

        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->route('profile')->with('update-password', 'Successfully Updated Password');
    }

    public function wallet()
    {
        $user = Auth::user();
        return view('frontend.wallet', compact('user'));
    }

    public function transfer()
    {
        $user = Auth::user();
        return view('frontend.transfer', compact('user'));
    }

    
    public function toAccountVerify(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();

        if($request->phone == Auth::user()->phone) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Doesn\'t transfer your account!'
            ]);
        }

        if($user) {
            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'Transfer account is invalid'
        ]);
    }

    public function transferConfirm(ConfirmTransferRequest $request)
    {
        $hash_value2 = hash_hmac('sha256', $request->phone.$request->amount.$request->description , 'magicpay123!@#');  

        if($request->hash_value !== $hash_value2) {
            return redirect()->route('transfer')->withErrors(['amount' => 'The given data isn\'t be secure!'])->withInput();
        }

        $from_account = Auth::user();
        
        if($from_account->wallet->amount < $request->amount) {
            return back()->withErrors(['fail' => '​ငွေမလုံလောက်ပါ။'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if($to_account && $to_account->phone != Auth::user()->phone) {
            $amount = $request->amount;
            $description = $request->description;
            $hash_value = $request->hash_value;

            return view('frontend.transfer_confirm', compact('hash_value','from_account', 'to_account', 'amount', 'description'));
        }

        return back()->withErrors(['fail' => 'Transfer account is invalid'])->withInput();

    }

    public function transferComplete(ConfirmTransferRequest $request)
    {
        $hash_value2 = hash_hmac('sha256', $request->phone.$request->amount.$request->description , 'magicpay123!@#');

        if($request->hash_value !== $hash_value2) {
            return redirect()->route('transfer')->withErrors(['amount' => 'The given data isn\'t be secure!'])->withInput();
        }

        $from_account = Auth::user();

        if($from_account->wallet->amount < $request->amount) {
            return back()->withErrors(['fail' => '​ငွေမလုံလောက်ပါ။'])->withInput();
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if($to_account && $to_account->phone != Auth::user()->phone) {
            $amount = $request->amount;
            $description = $request->description;

            if(!$from_account->wallet || !$to_account->wallet) {
                return back()->withErrors(['fail' => 'Something Wrong. The given data is invalid'])->withInput();
            }

            DB::beginTransaction();

            try {
                $from_account->wallet->decrement('amount', $amount);
                $from_account->wallet->update();
    
                $to_account->wallet->increment('amount', $amount);
                $to_account->wallet->update();

                $ref_no = UUIDGenerate::refNumber();
                
                $from_account_transaction = new Transaction();
                $from_account_transaction->ref_no = $ref_no;
                $from_account_transaction->trx_id = UUIDGenerate::trxId();
                $from_account_transaction->user_id = $from_account->id;
                $from_account_transaction->type = 2;
                $from_account_transaction->amount = $amount;
                $from_account_transaction->source_id = $to_account->id;
                $from_account_transaction->description = $description;
                $from_account_transaction->save();

                $to_account_transaction = new Transaction();
                $to_account_transaction->ref_no = $ref_no;
                $to_account_transaction->trx_id = UUIDGenerate::trxId();
                $to_account_transaction->user_id = $to_account->id;
                $to_account_transaction->type = 1;
                $to_account_transaction->amount = $amount;
                $to_account_transaction->source_id = $from_account->id;
                $to_account_transaction->description = $description;
                $to_account_transaction->save();

                DB::commit();
                return redirect()->route('transaction.detail', $from_account_transaction->id)->with('transfer-success', 'အောင်မြင်ပါသည်။');
            } catch (\Exception $e) {
                DB::rollback();
                return back()->withErrors(['fail' => 'Something Wrong.'])->withInput();
            }
        }

        return back()->withErrors(['fail' => 'Transfer account is invalid'])->withInput();
    }

    public function passwordCheck(Request $request)
    {
        if(!$request->password) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please fill your password!'
            ]);
        }

        $user = Auth::user();
        if(Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'success',
                'message' => 'The password is correct.'
            ]);
        }

        return response()->json([
            'status' => 'fail',
            'message' => 'The password is incorrect.'
        ]);
    }

    public function transaction(Request $request)
    {
        $user = Auth::user();
        $transactions = Transaction::with('user', 'source')->where('user_id', $user->id)->orderBy('created_at', 'DESC');

        if($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        if($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        $transactions = $transactions->paginate(5);
        return view('frontend.transaction', compact('transactions'));
    }

    public function transactionDetail(Transaction $trx)
    {
       $user = Auth::user();
       $transaction = Transaction::with('user', 'source')->where('user_id', $user->id)->where('trx_id', $trx->trx_id)->first();
       return view('frontend.transaction_detail', compact('transaction'));
    }

    public function transferHash(Request $request)
    {
        $hash_value = hash_hmac('sha256', $request->phone.$request->amount.$request->description , 'magicpay123!@#'); 

        return response()->json([
            'status' => 'success',
            'data' => $hash_value
        ]);
    }

    public function receiveQR()
    {
        return view('frontend.receiveqr');
    }
}
