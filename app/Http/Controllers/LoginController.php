<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\EmployeeCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    public function login()
    {
        return view('authentication.login');
    }

    public function authenticate(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('authentication.login')->withInput()->withErrors($validator);
        }

        // Try logging in as a User
        $user = User::where('employee_code', $request->employee_code)->first();
        if ($user && $user->pan_card_no === $request->password) {
            Auth::guard('web')->login($user);

            return redirect()->route('pages.dashboard');
        }

        return redirect()->route('authentication.login')->with('error', 'Either Employee Code or Password is incorrect.');
    }


    public function logout()
    {
        Auth::logout();
        return redirect()->route('authentication.login');
    }

}
