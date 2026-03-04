<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LoginController extends Controller
{
    /**
     * Handle an AJAX login request, returning JSON.
     */
    public function ajaxLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        try {
            $credentials = $request->only('email', 'password');
            $remember    = $request->boolean('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();
                return response()->json([
                    'success'  => true,
                    'redirect' => route('admin.dashboard'),
                ]);
            }

            return response()->json([
                'error' => 'Email atau password salah.',
            ], 422);
        } catch (Throwable $e) {
            report($e);
            return response()->json([
                'error' => 'Terjadi kesalahan. Silakan coba lagi.',
            ], 500);
        }
    }
}
