<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Trending = latest listings
        $trending = Listing::orderBy('createdAt', 'desc')->limit(12)->get();

        // Top sellers by listing count
        $topSellerIds = DB::table('Listing')
            ->select('sellerId', DB::raw('COUNT(*) as cnt'))
            ->groupBy('sellerId')
            ->orderByDesc('cnt')
            ->limit(6)
            ->pluck('sellerId');

        $sellers = User::whereIn('id', $topSellerIds)->get();

        return view('web.home', [
            'trending' => $trending,
            'sellers'  => $sellers,
        ]);
    }
}

