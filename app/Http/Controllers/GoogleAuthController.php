<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->with([
                'prompt' => 'consent',
                'access_type' => 'offline',
            ])
            ->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('id_google', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name ?? 'User',
                'email' => $googleUser->email,
                'id_google' => $googleUser->id,
                'password' => bcrypt(Str::random(32)),
            ]);
        } else {
            $user->update([
                'id_google' => $user->id_google ?? $googleUser->id,
            ]);
        }

        // Generate OTP 6 digit
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP ke DB
        $user->update([
            'otp' => $otp,
        ]);

        // ✅ Simpan session pakai $request->session() (lebih konsisten)
        $request->session()->put('otp_user_id', $user->id);

        // Kirim OTP
        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->route('otp.show')
            ->with('success', 'OTP sudah dikirim ke email kamu.');
    }
}