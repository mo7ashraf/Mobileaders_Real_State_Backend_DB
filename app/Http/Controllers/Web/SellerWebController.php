<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SellerProfile;
use App\Models\Listing;
use Illuminate\Http\Request;

class SellerWebController extends Controller
{
    public function index(Request $r)
    {
        $q = User::query();
        // Only users who have a seller profile
        $q->whereIn('id', SellerProfile::query()->pluck('userId'));

        if ($s = $r->get('q')) {
            $q->where('name', 'like', "%$s%");
        }

        $sellers = $q->orderBy('name')->paginate(24)->withQueryString();

        // Map profiles for quick access
        $profiles = SellerProfile::whereIn('userId', $sellers->pluck('id'))
            ->get()->keyBy('userId');

        return view('web.sellers', [
            'sellers' => $sellers,
            'profiles' => $profiles,
        ]);
    }

    public function show(string $id)
    {
        $seller = User::findOrFail($id);
        $profile = SellerProfile::where('userId', $id)->first();
        $listings = Listing::where('sellerId', $id)->orderBy('createdAt', 'desc')->paginate(12);

        return view('web.seller-show', [
            'seller'   => $seller,
            'profile'  => $profile,
            'listings' => $listings,
        ]);
    }
}
