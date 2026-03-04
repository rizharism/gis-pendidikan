<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class ForgotPasswordController extends Controller
{
    /**
     * Send a 6-digit numeric reset code to the user's email.
     * Rate-limited to 5 attempts per minute per email.
     */
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;

        // Rate-limit: 5 attempts per minute per email
        $rateLimiterKey = 'password-reset-code:' . $email;
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            return response()->json([
                'error' => "Terlalu banyak permintaan. Coba lagi dalam {$seconds} detik.",
            ], 429);
        }
        RateLimiter::hit($rateLimiterKey, 60);

        try {
            $code = (string) random_int(100000, 999999);

            DB::transaction(function () use ($email, $code) {
                // Remove old codes for this email
                DB::table('password_reset_codes')->where('email', $email)->delete();

                // Store the new hashed code
                DB::table('password_reset_codes')->insert([
                    'email'      => $email,
                    'token'      => Hash::make($code),
                    'attempts'   => 0,
                    'expires_at' => now()->addMinutes(15),
                    'created_at' => now(),
                ]);
            });

            // Send the plain code via email (outside transaction – network is not transactional)
            Mail::to($email)->send(new PasswordResetCodeMail($code));

            return response()->json(['sent' => true]);
        } catch (Throwable $e) {
            report($e);
            return response()->json([
                'error' => 'Gagal mengirim kode. Silakan coba lagi.',
            ], 500);
        }
    }
}
