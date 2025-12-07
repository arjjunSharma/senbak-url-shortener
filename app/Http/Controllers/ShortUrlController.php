<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (Gate::denies('view-short-url-index')) {
            abort(403);
        }

        $query = ShortUrl::query()->with('creator', 'company');

        if ($user->isAdmin()) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('company_id')
                  ->orWhere('company_id', '!=', $user->company_id);
            });
        } elseif ($user->isMember()) {
            $query->where('user_id', '!=', $user->id);
        }

        $shortUrls = $query->latest()->paginate(15);

        return view('short-urls.index', [
            'shortUrls' => $shortUrls,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (Gate::denies('create-short-url')) {
            abort(403, 'You are not allowed to create short URLs.');
        }

        $data = $request->validate([
            'original_url' => 'required|url|max:2048',
        ]);

        $code = $this->generateUniqueCode();

        ShortUrl::create([
            'code'        => $code,
            'original_url'=> $data['original_url'],
            'user_id'     => $user->id,
            'company_id'  => $user->company_id,
        ]);

        return redirect()->route('short-urls.index')
            ->with('status', "Short URL created: {$code}");
    }

    protected function generateUniqueCode(): string
    {
        do {
            $code = Str::random(8);
        } while (ShortUrl::where('code', $code)->exists());

        return $code;
    }

    // Not public: route is inside auth middleware
    public function resolve(Request $request, string $code)
    {
        $shortUrl = ShortUrl::where('code', $code)->firstOrFail();

        return redirect()->away($shortUrl->original_url);
    }
}
