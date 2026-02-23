<form method="POST" action="{{ route('otp.send') }}">
  @csrf
  <input name="email" type="email" placeholder="Email" required>
  <button type="submit">Kirim OTP</button>
  @error('email')<div>{{ $message }}</div>@enderror
  @if(session('status'))<div>{{ session('status') }}</div>@endif
</form>