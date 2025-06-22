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
use function Laravel\Prompts\error;


class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_code' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'messages' => $validator->errors()
                ], 422);
            }
            return redirect()->route('login')->withInput()->withErrors($validator);
        }

        $user = User::where('employee_code', $request->employee_code)->first();
        if ($user && $user->pan_card_no === $request->password) {
            Auth::guard('web')->login($user);

            session([
                'employee_code' => Auth::user()->employee_code,
                'employee_name' => Auth::user()->employee_name ?? (Auth::user()->employee->employee_name ?? 'Unknown'),
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'redirect_url' => route('dashboard')
                ]);
            }

            return redirect()->route('dashboard');
        }

        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        return redirect()->route('login')->with('error', 'Either Employee Code or Password is incorrect.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
