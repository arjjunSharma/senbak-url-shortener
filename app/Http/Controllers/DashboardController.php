<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ShortUrl;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $data = [];

        if ($user->isSuperAdmin()) {
            $data['clients'] = Company::withCount(['users', 'shortUrls'])->get();
            $data['shortUrls'] = ShortUrl::with('company')->latest()->limit(10)->get();
        }

        return view('dashboard.index', $data);
    }
}
