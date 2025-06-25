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
    // public function authenticate(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'employee_code' => 'required',
    //         'password' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'messages' => $validator->errors()
    //             ], 422);
    //         }
    //     }

    //     $user = User::where('employee_code', $request->employee_code)->first();
    //     if ($user && $user->pan_card_no === $request->password) {
    //         if (empty($user->telegram_chat_id)) {
    //             if ($request->filled('telegram_chat_id')) {
    //                 $user->chat_id = $request->telegram_chat_id;
    //                 $user->save();
    //                 $this->sendTelegramMessage($user->telegram_chat_id, "Welcome!\nYour chat ID has been registered.\n\nStay informed with the latest updates on this Telegram channel.");
    //             } else {
    //                 return redirect()->route('login')
    //                     ->withInput() // Retains form values
    //                     ->with('show_chat_id', true);
    //             }
    //         }

    //         Auth::guard('web')->login($user);

    //         session([
    //             'employee_code' => Auth::user()->employee_code,
    //             'employee_name' => Auth::user()->employee_name ?? (Auth::user()->employee->employee_name ?? 'Unknown'),
    //         ]);

    //         if ($request->ajax()) {
    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Login successful',
    //                 'redirect_url' => route('dashboard')
    //             ]);
    //         }
    //     }

    //     if ($request->ajax()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Invalid credentials'
    //         ], 401);
    //     }
    // }

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
        }

        $user = User::where('employee_code', $request->employee_code)->first();

        if ($user && $user->pan_card_no === $request->password) {
            // Check if Telegram chat ID is already saved
            if (empty($user->telegram_chat_id)) {
                if ($request->filled('telegram_chat_id')) {
                    $user->telegram_chat_id = $request->telegram_chat_id;
                    $user->save();

                    // Send welcome message
                    $this->sendTelegramMessage(
                        $user->telegram_chat_id,
                        "Welcome!\nYour chat ID has been registered.\n\nStay informed with the latest updates on this Telegram channel."
                    );
                } else {
                    // Ask for chat ID by showing QR input again
                    return response()->json([
                        'status' => 'need_chat_id',
                        'message' => 'Please scan QR and enter your Telegram Chat ID.',
                    ], 200);
                }
            }

            // Login the user
            Auth::login($user);

            session([
                'employee_code' => $user->employee_code,
                'employee_name' => $user->employee_name ?? 'Unknown',
                'mobile_no_personal' => $user->mobile_no_personal ?? 'Unknown',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'redirect_url' => route('dashboard'),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    protected function sendTelegramMessage($chatId, $message)
    {

        $telegramConfig = DB::table('telegram_rn')->where('name', 'task')->first();
        $botId = $telegramConfig->botId;

        $url = "https://api.telegram.org/bot{$botId}/sendMessage";

        $params = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'HTML'
        ];

        Http::post($url, $params);
    }
}
