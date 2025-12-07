@extends('layouts.app')

@section('content')
    <div class="card" style="max-width:420px;margin:40px auto;">
        <h2>Senbak URL Shortener</h2>

        <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <button type="submit" style="margin-top:6px;">Login</button>
        </form>

        <p class="small-text" style="margin-top:14px;">
            SuperAdmin test user:<br>
            Email: <strong>superadmin@example.com</strong><br>
            Password: <strong>superadmin_password</strong>
        </p>
    </div>
@endsection
