<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function show()
    {
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $userId = session('otp_user_id');

        if (!$userId) {
            return redirect()->route('login')
                ->with('error', 'Session OTP habis, login ulang.');
        }

        $user = User::findOrFail($userId);

        // ✅ FIX: kalau otp di database null, berarti OTP tidak tersimpan
        // biasanya karena kolom otp tidak masuk $fillable / kena guarded di model User
        if (empty($user->otp)) {
            session()->forget('otp_user_id');

            return redirect()->route('login')
                ->with('error', 'OTP tidak tersimpan di database. Cek kolom otp di tabel users dan pastikan otp bisa di-save (fillable/guarded). Silakan login ulang.');
        }

        if ((string) $user->otp !== (string) $request->otp) {
            return back()->with('error', 'OTP salah.');
        }

        $user->update(['otp' => null]);

        session()->forget('otp_user_id');

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}