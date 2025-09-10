<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;

class SearchWebController extends Controller
{
    public function index(Request $r)
    {
        $q = Listing::query();

        if ($s = $r->get('q')) {
            $q->where(function ($w) use ($s) {
                $w->where('title', 'like', "%$s%")
                  ->orWhere('address', 'like', "%$s%");
            });
        }
        if ($s = $r->get('city'))     $q->where('city', $s);
        if ($s = $r->get('category')) $q->where('category', $s);
        if ($s = $r->get('status'))   $q->where('status', $s);
        if ($s = $r->get('bedrooms')) $q->where('bedrooms', '>=', (int) $s);
        if ($s = $r->get('bathrooms'))$q->where('bathrooms','>=', (int) $s);

        if ($r->has('minPrice') || $r->has('maxPrice')) {
            $min = (int) $r->get('minPrice', 0);
            $max = (int) $r->get('maxPrice', PHP_INT_MAX);
            $q->whereBetween('price', [$min, $max]);
        }

        $sort = $r->get('sort');
        if      ($sort === 'price_asc')  $q->orderBy('price', 'asc');
        elseif  ($sort === 'price_desc') $q->orderBy('price', 'desc');
        else                             $q->orderBy('createdAt', 'desc');

        $listings = $q->paginate(24)->withQueryString();

        return view('web.search', [
            'listings' => $listings,
            'filters'  => $r->all(),
        ]);
    }
}

