@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Invite User</h2>

        <form method="POST" action="{{ route('invitations.store') }}">
            @csrf

            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="role">Role</label>
            <select id="role" name="role" required>
                <option value="">-- choose role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                @endforeach
            </select>
            @error('role') <div class="error">{{ $message }}</div> @enderror

            <label for="company_id">Existing Company (optional)</label>
            <select id="company_id" name="company_id">
                <option value="">-- none --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            @error('company_id') <div class="error">{{ $message }}</div> @enderror

            <label for="new_company">Or New Company Name</label>
            <input id="new_company" type="text" name="new_company" value="{{ old('new_company') }}">
            @error('new_company') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Temporary Password</label>
            <input id="password" type="password" name="password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <button type="submit">Send Invitation</button>
        </form>

        <p class="small-text" style="margin-top:10px;">
            SuperAdmin can’t invite an Admin into a newly created company.<br>
            Admins can’t invite another Admin or Member into their own company.
        </p>
    </div>
@endsection
