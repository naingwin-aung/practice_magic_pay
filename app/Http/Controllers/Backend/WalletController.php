<?php

namespace App\Http\Controllers\Backend;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddAmountRequest;


class WalletController extends Controller
{
    public function index()
    {
        return view('backend.wallet.index');
    }

    public function serverSideData()
    {
        $wallets = Wallet::with('user');

        return Datatables::of($wallets)
            ->addColumn('account_person', function ($each) {
                $user = $each->user;
                if ($user) {
                    return '
                        <p>Name: ' . $user->name . '</p>
                        <p>Email: ' . $user->email . '</p>
                        <p>Phone: ' . $user->phone . '</p>
                    ';
                }
                return '-';
            })
            ->editColumn('amount', function ($each) {
                return number_format($each->amount, 2) . ' MMK';
            })
            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->diffForHumans() . " - " .
                    Carbon::parse($each->created_at)->toFormattedDateString() . " - " .
                    Carbon::parse($each->created_at)->format('H:m:s');
            })
            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->created_at)->diffForHumans() . " - " .
                    Carbon::parse($each->created_at)->toFormattedDateString() . " - " .
                    Carbon::parse($each->created_at)->format('H:m:s');
            })
            ->rawColumns(['account_person', 'amount'])
            ->make(true);
    }

    public function addAmount()
    {
        $users = User::orderBy('name')->get();
        return view("backend.wallet.addamount", compact('users'));
    }

    public function addAmountStore(AddAmountRequest $request)
    {
        if($request->amount < 1000) {
            return back()->withErrors(['fail' => 'Your transfer amount greater than 1000 MMK'])->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::with('wallet')->where('id', $request->user_id)->firstOrFail();
            $amount = $request->amount;
            $user->wallet->increment('amount', $amount);
            $user->wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            $user_account_transaction = new Transaction();
            $user_account_transaction->ref_no = $ref_no;
            $user_account_transaction->trx_id = UUIDGenerate::trxId();
            $user_account_transaction->user_id = $user->id;
            $user_account_transaction->type = 1;
            $user_account_transaction->amount = $amount;
            $user_account_transaction->source_id = 0;
            $user_account_transaction->description = $request->description; 
            $user_account_transaction->save();

            DB::commit();
            return redirect()->route('admin.wallet.index')->with('created', 'Successfully added amount.');
         } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['fail' => 'Something Wrong'])->withInput();
        }
    }

    public function reduceAmount()
    {
        $users = User::orderBy('name')->get();
        return view("backend.wallet.reduceamount", compact('users'));
    }

    public function reduceAmountStore(AddAmountRequest $request)
    {
        if($request->amount < 1) {
            return back()->withErrors(['fail' => 'The amount must be at least 1 MMK'])->withInput();
        }

        DB::beginTransaction();
        try {
            $user = User::with('wallet')->where('id', $request->user_id)->firstOrFail();

            if($request->amount > $user->wallet->amount) {
                // return back()->withErrors(['fail' => 'Your Reduce Amount is invalid'])->withInput();
                throw new Exception('The amount is less than the wallet balance');
            }

            $amount = $request->amount;
            $user->wallet->decrement('amount', $amount);
            $user->wallet->update();

            $ref_no = UUIDGenerate::refNumber();
            $user_account_transaction = new Transaction();
            $user_account_transaction->ref_no = $ref_no;
            $user_account_transaction->trx_id = UUIDGenerate::trxId();
            $user_account_transaction->user_id = $user->id;
            $user_account_transaction->type = 2;
            $user_account_transaction->amount = $amount;
            $user_account_transaction->source_id = 0;
            $user_account_transaction->description = $request->description; 
            $user_account_transaction->save();

            DB::commit();
            return redirect()->route('admin.wallet.index')->with('created', 'Successfully reduced amount.');
         } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['fail' => 'Something Wrong.' . $e->getMessage()])->withInput();
        }
    }
}
