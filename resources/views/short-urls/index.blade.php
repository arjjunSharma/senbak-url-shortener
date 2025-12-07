@extends('layouts.app')

@section('content')
    <div class="card">
        <h2>Short URLs</h2>

        @can('create-short-url')
            <h3 style="margin-top:10px;">Create Short URL</h3>
            <form method="POST" action="{{ route('short-urls.store') }}">
                @csrf

                <label for="original_url">Long URL</label>
                <input id="original_url" type="url" name="original_url" value="{{ old('original_url') }}" required>
                @error('original_url') <div class="error">{{ $message }}</div> @enderror

                <button type="submit">Generate</button>
            </form>
        @else
            <p class="small-text">
                Your role ({{ auth()->user()->role }}) is not allowed to create short URLs.
            </p>
        @endcan

        <h3 style="margin-top:18px;">Visible Short URLs</h3>
        <table>
            <thead>
            <tr>
                <th>Code</th>
                <th>Original URL</th>
                <th>Created By</th>
                <th>Company</th>
            </tr>
            </thead>
            <tbody>
            @forelse($shortUrls as $su)
                <tr>
                    <td>{{ $su->code }}</td>
                    <td>{{ $su->original_url }}</td>
                    <td>{{ $su->creator->name ?? '-' }}</td>
                    <td>{{ $su->company->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="small-text">Nothing to show yet.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        <div style="margin-top:10px;">
            {{ $shortUrls->links() }}
        </div>
    </div>
@endsection
