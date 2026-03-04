<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    /**
     * Display the admin's profile.
     */
    public function index(): View
    {
        return view('admin.profile.index');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        try {
            DB::transaction(function () use ($user, $request) {
                $user->fill($request->validated());

                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }

                $user->save();
            });

            return Redirect::route('admin.profile')->with('status', 'profile-updated');
        } catch (Throwable $e) {
            report($e);

            return Redirect::route('admin.profile')
                ->with('error', 'Gagal memperbarui profil. Silakan coba lagi.');
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.required'                  => 'Kata sandi baru wajib diisi.',
            'password.min'                        => 'Kata sandi minimal 8 karakter.',
            'password.confirmed'                  => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $request->user()->update([
                    'password' => Hash::make($request->password),
                ]);
            });

            return Redirect::route('admin.profile')->with('status', 'password-updated');
        } catch (Throwable $e) {
            report($e);

            return Redirect::route('admin.profile')
                ->with('error', 'Gagal mengubah kata sandi. Silakan coba lagi.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        try {
            DB::transaction(function () use ($user, $request) {
                Auth::logout();
                $user->delete();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            });

            return Redirect::to('/');
        } catch (Throwable $e) {
            report($e);

            return Redirect::route('admin.profile')
                ->with('error', 'Gagal menghapus akun. Silakan coba lagi.');
        }
    }
}
