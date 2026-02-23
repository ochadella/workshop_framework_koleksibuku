<form method="POST" action="{{ route('otp.verify') }}">
  @csrf
  <input name="email" type="email" value="{{ old('email', $email) }}" required>
  <input name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" placeholder="6 digit OTP" required>
  <button type="submit">Verifikasi</button>
  @error('code')<div>{{ $message }}</div>@enderror
  @if(session('status'))<div>{{ session('status') }}</div>@endif
</form>