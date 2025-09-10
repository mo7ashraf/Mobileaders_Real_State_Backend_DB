<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;

class ListingWebController extends Controller
{
    public function show(string $id)
    {
        $item = Listing::findOrFail($id);
        $seller = User::find($item->sellerId);

        // Similar by category/city
        $q = Listing::query()->where('id', '!=', $id);
        if ($item->category) $q->where('category', $item->category);
        if ($item->city)     $q->where('city',     $item->city);
        $similar = $q->orderBy('createdAt', 'desc')->limit(10)->get();

        return view('web.listing-show', [
            'item'    => $item,
            'seller'  => $seller,
            'similar' => $similar,
        ]);
    }
}

