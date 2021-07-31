<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Helpers\UUIDGenerate;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\LoginUserRequest;
use App\Http\Requests\Api\StoreUserRequest;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->ip = $request->ip();
            $user->user_agent = $request->server('HTTP_USER_AGENT');
            $user->login_at = Carbon::now();
            $user->save();

            Wallet::firstOrCreate(
                [
                    'user_id' => $user->id,
                ],
                [
                    'account_number' => UUIDGenerate::accountNumber(),
                    'amount' => 0
                ]
            );
            DB::commit();

            $token = $user->createToken('Magic Pay')->accessToken;
            return success('Successfully Register', ['token' => $token]);
        } catch (\Exception $e) {
            DB::rollback();
            return fail('Register Fail', 'fail');
        }
    }

    public function login(LoginUserRequest $request)
    {
        if(Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $user = auth()->user();
            $user->ip = $request->ip();
            $user->user_agent = $request->server('HTTP_USER_AGENT');
            $user->login_at = Carbon::now();
            $user->update();

            $token = $user->createToken('Magic Pay')->accessToken;

            return success('Successfully Login', ['token' => $token]);
        }

        return fail('These credentials do not match our records', 'Fail');
    }

    public function logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        return success("Successfully Logout", null);
    }
}
