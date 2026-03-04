<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ResetPasswordController extends Controller
{
    /**
     * Verify the numeric code and reset the user's password.
     * Max 5 wrong attempts before the code is invalidated.
     */
    public function resetWithCode(Request $request)
    {
        $request->validate([
            'email'                 => ['required', 'email', 'exists:users,email'],
            'code'                  => ['required', 'digits:6'],
            'password'              => ['required', 'min:8', 'confirmed'],
        ]);

        $record = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->first();

        // Check if a code exists
        if (! $record) {
            return response()->json(['error' => 'Kode tidak ditemukan. Silakan minta kode baru.'], 422);
        }

        // Check expiry
        if (now()->isAfter($record->expires_at)) {
            DB::table('password_reset_codes')->where('email', $request->email)->delete();
            return response()->json(['error' => 'Kode sudah kedaluwarsa. Silakan minta kode baru.'], 422);
        }

        // Check max attempts (5)
        if ($record->attempts >= 5) {
            DB::table('password_reset_codes')->where('email', $request->email)->delete();
            return response()->json(['error' => 'Terlalu banyak percobaan. Silakan minta kode baru.'], 422);
        }

        // Verify code
        if (! Hash::check($request->code, $record->token)) {
            DB::table('password_reset_codes')
                ->where('email', $request->email)
                ->increment('attempts');
            $remaining = 5 - ($record->attempts + 1);
            return response()->json(['error' => "Kode salah. Sisa percobaan: {$remaining}."], 422);
        }

        try {
            DB::transaction(function () use ($request) {
                // Update user's password
                User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

                // Remove the used code
                DB::table('password_reset_codes')
                    ->where('email', $request->email)
                    ->delete();
            });

            return response()->json(['success' => true]);
        } catch (Throwable $e) {
            report($e);
            return response()->json([
                'error' => 'Gagal mereset password. Silakan coba lagi.',
            ], 500);
        }
    }
}
