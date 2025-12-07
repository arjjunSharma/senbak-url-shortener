@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Dashboard</h2>
        <p class="small-text">
            Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }}).
        </p>

        @if(auth()->user()->isSuperAdmin())
            <h3 style="margin-top:18px;">Clients</h3>
            <table>
                <thead>
                <tr>
                    <th>Company</th>
                    <th>Users</th>
                    <th>Short URLs</th>
                </tr>
                </thead>
                <tbody>
                @foreach($clients as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->users_count }}</td>
                        <td>{{ $company->short_urls_count }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h3 style="margin-top:22px;">Recent Short URLs</h3>
            <table>
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Company</th>
                    <th>Original URL</th>
                </tr>
                </thead>
                <tbody>
                @foreach($shortUrls as $su)
                    <tr>
                        <td>{{ $su->code }}</td>
                        <td>{{ $su->company->name ?? '-' }}</td>
                        <td>{{ $su->original_url }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @elseif(auth()->user()->isAdmin())
            <p>Use the <strong>Invitations</strong> tab to invite team members, and
                <strong>Short URLs</strong> to view URLs created in other companies.</p>
        @elseif(auth()->user()->isMember())
            <p>You can view short URLs created by others in your company from the
                <strong>Short URLs</strong> tab. Members can’t create new short URLs.</p>
        @else
            <p>You can create new short URLs and view your existing ones from the
                <strong>Short URLs</strong> tab.</p>
        @endif
    </div>
@endsection
