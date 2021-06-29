<?php

namespace App\Http\Controllers\Backend;

use App\Models\Admin;
use Jenssegers\Agent\Agent;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminUser;
use App\Http\Requests\UpdateAdminUser;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    public function index()
    {
        return view('backend.admin_user.index');
    }

    public function create()
    {
        return view('backend.admin_user.create');
    }

    public function store(StoreAdminUser $request)
    {
        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.admin-user.index')->with('created', 'Successfully Created');
    }

    public function edit(Admin $admin_user)
    {
        return view('backend.admin_user.edit', compact('admin_user'));
    }

    public function update(UpdateAdminUser $request, $id)
    {
        $admin_user = Admin::findOrFail($id);
        $admin_user->name = $request->name;
        $admin_user->email = $request->email;
        $admin_user->phone = $request->phone;
        $admin_user->password = $request->password ? Hash::make($request->password): $admin_user->password;
        $admin_user->update();

        return redirect()->route('admin.admin-user.index')->with('updated', 'Successfully Updated');

    }

    public function destroy($id)
    {
        $admin_user = Admin::findOrFail($id);
        $admin_user->delete();

        return 'success';
    }

    public function serverSideData()
    {
        $admin = Admin::query();

        return Datatables::of($admin)
        ->editColumn('user_agent', function($each) {
            if($each->user_agent) {
                $agent = new Agent();
                $agent->setUserAgent($each->user_agent);
                $device = $agent->device();
                $platform = $agent->platform();
                $browser = $agent->browser();
    
                return '<table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Device</td>
                            <td>'.$device.'</td>
                        </tr>
                        <tr>
                            <td>Platform</td>
                            <td>'.$platform.'</td>
                        </tr>
                        <tr>
                            <td>Browser</td>
                            <td>'.$browser.'</td>
                        </tr>
                    </tbody>
                </table>';
            }

            return '-';
        })
        ->editColumn('created_at', function($each) {
            return Carbon::parse($each->created_at)->diffForHumans() . " - " .
            Carbon::parse($each->created_at)->toFormattedDateString() . " - " .
            Carbon::parse($each->created_at)->format('H:m:s');
        })
        ->editColumn('updated_at', function($each) {
            return Carbon::parse($each->created_at)->diffForHumans() . " - " .
            Carbon::parse($each->created_at)->toFormattedDateString() . " - " .
            Carbon::parse($each->created_at)->format('H:m:s');
        })
        ->addColumn('action', function($each) {
            if($each->id == auth()->guard('admin')->user()->id) {
                return 'You are now login';
            }

            $edit_icon = '<a href="'.route('admin.admin-user.edit', $each->id).'" class="text-warning edit-icon"><i class="fas fa-edit"></i></a>';
            $delete_icon = '<a href="#" class="text-danger delete" data-id="'.$each->id.'"><i class="fas fa-trash-alt"></i></a>';
            return '<div class="action-icon">' . $edit_icon . $delete_icon  . '</div>';
        })
        ->rawColumns(['user_agent', 'action'])
        ->make(true);
    }
}
