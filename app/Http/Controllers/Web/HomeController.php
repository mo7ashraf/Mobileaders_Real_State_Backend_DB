<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $r)
    {
        // Filters from query string (optional)
        $status   = $r->query('status'); // 'rent' | 'sell'
        $category = $r->query('category');

        // Trending = latest listings, filtered when requested
        $trendingQ = Listing::query();
        if (in_array($status, ['rent','sell'], true)) {
            $trendingQ->where('status', $status);
        }
        if ($category) {
            $trendingQ->where('category', $category);
        }
        $trending = $trendingQ->orderBy('createdAt', 'desc')->limit(12)->get();

        // Top sellers by listing count
        $topSellerIds = DB::table('listing')
            ->select('sellerId', DB::raw('COUNT(*) as cnt'))
            ->groupBy('sellerId')
            ->orderByDesc('cnt')
            ->limit(6)
            ->pluck('sellerId');

        $sellers = User::whereIn('id', $topSellerIds)->get();

        $categories = Category::where('enabled',true)->orderBy('sortOrder')->orderBy('name')->get();

        return view('web.home', [
            'trending'   => $trending,
            'sellers'    => $sellers,
            'categories' => $categories,
            'filters'    => [
                'status'   => $status,
                'category' => $category,
            ],
        ]);
    }
}
