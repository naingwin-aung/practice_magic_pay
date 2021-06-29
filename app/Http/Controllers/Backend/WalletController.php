<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\Wallet;
use Yajra\DataTables\Datatables;
use App\Http\Controllers\Controller;


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
                return '' . number_format($each->amount, 2) . ' ks';
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
}
