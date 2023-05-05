<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    //
    public function showForgetPasswordForm(): Response
{
    return response()->json(['message' => 'Show forget password form'], 200);
}

public function submitForgetPasswordForm(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users',
    ]);

    $token = Str::random(64);

    DB::table('password_resets')->insert([
        'email' => $request->email, 
        'token' => $token, 
        'created_at' => Carbon::now()
    ]);

    Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
        $message->to($request->email);
        $message->subject('Reset Password');
    });

    return response()->json(['message' => 'We have e-mailed your password reset link!']);
}


public function showResetPasswordForm(Request $request, $token)
    { 
        return response()->json(['token' => $token]);
    }


    public function submitResetPasswordForm(Request $request): JsonResponse
{
    $request->validate([
        'email' => 'required|email|exists:users',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' => 'required'
    ]);

    $updatePassword = DB::table('password_resets')
        ->where([
            'email' => $request->email, 
            'token' => $request->token
        ])
        ->first();

    if (!$updatePassword) {
        return response()->json(['error' => 'Invalid token!'], 422);
    }

    $user = User::where('email', $request->email)
        ->update(['password' => Hash::make($request->password)]);

    DB::table('password_resets')->where(['email' => $request->email])->delete();

    return response()->json(['message' => 'Your password has been changed!'], 200);
}



}
