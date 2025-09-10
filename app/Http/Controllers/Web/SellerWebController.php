<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SellerProfile;
use App\Models\Listing;

class SellerWebController extends Controller
{
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

