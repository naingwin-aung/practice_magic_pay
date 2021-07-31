<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;
use App\Notifications\GeneralNotification;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\NotificationResource;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\ConfirmTransferRequest;
use App\Http\Resources\toAccountVerifyResource;
use App\Http\Resources\TransactionDetailResource;
use App\Http\Resources\NotificationDetailResource;

class PageController extends Controller
{
    public function profile()
    {
       $user = Auth::user();
       $data = new ProfileResource($user); 

       return success('success', $data);
    }

    public function transaction(Request $request)
    {
        $user = Auth::user();
        $transactions = Transaction::with('user', 'source')->where('user_id', $user->id)->orderBy('created_at', 'DESC');

        if($request->date) {
            $transactions = $transactions->whereDate('created_at', $request->date);
        }

        if($request->type) {
            $transactions = $transactions->where('type', $request->type);
        }

        $transactions = $transactions->paginate(5);

        return TransactionResource::collection($transactions)->additional(['result' => 1, 'message' => 'success']);
    }

    public function transactionDetail($trx_id)
    {
        $user = Auth::user();
        $transaction = Transaction::with('user', 'source')->where('user_id', $user->id)->where('trx_id', $trx_id)->firstOrFail();

        $data = new TransactionDetailResource($transaction);

        return success('success', $data);
    }

    public function notification()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(5);

        return NotificationResource::collection($notifications)->additional(['result' => 1, 'message' => 'success']);
    }

    public function notificationDetail($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->firstOrFail();
        $notification->markAsRead();

        $data = new NotificationDetailResource($notification);

        return success('success', $data);
    }

    public function toAccountVerify(Request $request)
    {
        if($request->to_phone) {
            $user = Auth::user();
            
            $to_phone = User::where('phone', $request->to_phone)->first();
    
            if($to_phone && $user->phone !== $to_phone->phone) {
                $data = new toAccountVerifyResource($to_phone);

                return success('success', $data);
            }

            return fail('Transfer phone number is invalid', null);
        }

        return fail('Please fill the phone number', null);
    }

    public function transferConfirm(ConfirmTransferRequest $request)
    {
        $hash_value2 = hash_hmac('sha256', $request->to_phone.$request->amount.$request->description , 'magicpay123!@#');  

        if($request->hash_value !== $hash_value2) {
            return fail('The given data isn\'t be secure!', null);
        }

        $from_account = Auth::user();
        
        if($from_account->wallet->amount < $request->amount) {
            return fail('​ငွေမလုံလောက်ပါ။', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if($to_account && $to_account->phone != Auth::user()->phone) {
            $amount = $request->amount;
            $description = $request->description;
            $hash_value = $request->hash_value;

            return success('success', [
                'from_name' => $from_account->name, 
                'from_phone' => $from_account->phone, 

                'to_name' => $to_account->name, 
                'to_phone' => $to_account->phone, 

                'amount' => $amount,
                'description' => $description,
                'hash_value' => $hash_value,
            ]);
        }

        return fail('Transfer account is invalid', null);
    }

    public function transferComplete(ConfirmTransferRequest $request)
    {
        if(!$request->password) {
            return fail('Please Fill your password', null);
        }

        $user = Auth::user();
        if(!Hash::check($request->password, $user->password)) {
            return fail('The password is incorrect.', null);
        }

        $hash_value2 = hash_hmac('sha256', $request->to_phone.$request->amount.$request->description , 'magicpay123!@#');

        if($request->hash_value !== $hash_value2) {
            return fail('The given data isn\'t be secure!', null);
        }

        $from_account = Auth::user();

        if($from_account->wallet->amount < $request->amount) {
            return fail('​ငွေမလုံလောက်ပါ။', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if($to_account && $to_account->phone != Auth::user()->phone) {
            $amount = $request->amount;
            $description = $request->description;

            if(!$from_account->wallet && !$to_account->wallet) {
                return fail('Something Wrong. The given data is invalid', null);
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

                //From Noti
                $title = 'Money Transfered!';
                $message = 'Your wallet transfered ' . number_format($amount) . ' MMK to '. $to_account->name . ' ('. $to_account->phone .')';
                $sourceable_id = $from_account_transaction->id; 
                $sourceable_type = Transaction::class; 
                $web_link = url('/transaction/detail/' . $from_account_transaction->trx_id);
                $deep_link = [
                    'target' => 'transaction_detail',
                    'parameter' => [
                        'trx_id' => $from_account_transaction->trx_id,
                    ]
                ];

                Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

                //To Noti
                $title = 'Money Received!';
                $message = 'Your wallet received ' . number_format($amount) . ' MMK from '. $from_account->name . ' ('. $from_account->phone .')';
                $sourceable_id = $to_account_transaction->id; 
                $sourceable_type = Transaction::class; 
                $web_link = url('/transaction/detail/' . $to_account_transaction->trx_id);
                $deep_link = [
                    'target' => 'transaction_detail',
                    'parameter' => [
                        'trx_id' => $to_account_transaction->trx_id,
                    ]
                ];
        
                Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

                DB::commit();
                return success('အောင်မြင်ပါသည်။', [
                    'trx_id' => $from_account_transaction->trx_id,
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return fail('Something Wrong.', null);
            }
        }

        return fail('Transfer account is invalid', null);
    }

    public function scanAndPayForm(Request $request)
    {
        if($request->to_phone) {
            $user = Auth::user();
            $to_account = User::where('phone',$request->to_phone)->first();
            
            if($to_account && $to_account->phone != $user->phone) {
                return success('Success', [
                    'from_name' => $user->name,
                    'from_phone' => $user->phone,
                    'to_name' => $to_account->name,
                    'to_phone' => $to_account->phone,
                ]);
            }
    
            return fail('QR code is invalid', null);
        }

        return fail('Phone number is invalid', null);
    }

    public function scanAndPayConfirm(ConfirmTransferRequest $request)
    {
        $hash_value2 = hash_hmac('sha256', $request->to_phone.$request->amount.$request->description , 'magicpay123!@#');
        
        if($request->hash_value !== $hash_value2) {
            return fail('The given data is no\'t secure!', null);
        }

        $from_account = Auth::user();

        if($from_account->wallet->amount < $request->amount) {
            return fail('​ငွေမလုံလောက်ပါ။', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if($to_account && $to_account->phone != $from_account->phone) {
            $amount = $request->amount;
            $description = $request->description;
            $hash_value = $request->hash_value;

            return success('success', [
                'from_name' => $from_account->name, 
                'from_phone' => $from_account->phone, 

                'to_name' => $to_account->name, 
                'to_phone' => $to_account->phone, 

                'amount' => $amount,
                'description' => $description,
                'hash_value' => $hash_value,
            ]);
        }

        return fail('Transfer account is invalid', null);
    }

    public function scanAndPayComplete(ConfirmTransferRequest $request)
    {
        if(!$request->password) {
            return fail('Please Fill your password', null);
        }

        $user = Auth::user();
        if(!Hash::check($request->password, $user->password)) {
            return fail('The password is incorrect.', null);
        }

        $hash_value2 = hash_hmac('sha256', $request->to_phone.$request->amount.$request->description , 'magicpay123!@#');

        if($request->hash_value !== $hash_value2) {
            return fail('The given data isn\'t be secure!', null);
        }

        $from_account = Auth::user();

        if($from_account->wallet->amount < $request->amount) {
            return fail('​ငွေမလုံလောက်ပါ။', null);
        }

        $to_account = User::where('phone', $request->to_phone)->first();

        if($to_account && $to_account->phone != Auth::user()->phone) {
            $amount = $request->amount;
            $description = $request->description;

            if(!$from_account->wallet && !$to_account->wallet) {
                return fail('Something Wrong. The given data is invalid', null);
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

                //From Noti
                $title = 'Money Transfered!';
                $message = 'Your wallet transfered ' . number_format($amount) . ' MMK to '. $to_account->name . ' ('. $to_account->phone .')';
                $sourceable_id = $from_account_transaction->id; 
                $sourceable_type = Transaction::class; 
                $web_link = url('/transaction/detail/' . $from_account_transaction->trx_id);
                $deep_link = [
                    'target' => 'transaction_detail',
                    'parameter' => [
                        'trx_id' => $from_account_transaction->trx_id,
                    ]
                ];

                Notification::send([$from_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

                //To Noti
                $title = 'Money Received!';
                $message = 'Your wallet received ' . number_format($amount) . ' MMK from '. $from_account->name . ' ('. $from_account->phone .')';
                $sourceable_id = $to_account_transaction->id; 
                $sourceable_type = Transaction::class; 
                $web_link = url('/transaction/detail/' . $to_account_transaction->trx_id);
                $deep_link = [
                    'target' => 'transaction_detail',
                    'parameter' => [
                        'trx_id' => $to_account_transaction->trx_id,
                    ]
                ];
        
                Notification::send([$to_account], new GeneralNotification($title, $message, $sourceable_id, $sourceable_type, $web_link, $deep_link));

                DB::commit();
                return success('အောင်မြင်ပါသည်။', [
                    'trx_id' => $from_account_transaction->trx_id,
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return fail('Something Wrong.', null);
            }
        }

        return fail('Transfer account is not valid', null);
    }
}
