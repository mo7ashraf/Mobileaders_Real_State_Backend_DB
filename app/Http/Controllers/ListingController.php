<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;

class ListingController extends Controller
{
    private function mapCategory($cat)
    {
        $map = [
            'شقة'=>'apartment','شقق'=>'apartment','apartment'=>'apartment',
            'فيلا'=>'villa','فلل'=>'villa','villa'=>'villa',
            'office'=>'office','مكتب'=>'office','مكاتب'=>'office',
            'استراحة'=>'resthouse','استراحات'=>'resthouse','resthouse'=>'resthouse',
        ];
        $s = mb_strtolower(trim((string)$cat));
        return $map[$s] ?? $cat;
    }

    private function mapStatus($st)
    {
        $s = mb_strtolower(trim((string)$st));
        if (in_array($s, ['rent','للإيجار'])) return 'rent';
        if (in_array($s, ['sell','للبيع']))   return 'sell';
        return $st;
    }

    public function index(Request $r)
    {
        $q = Listing::query()->with(['seller:id,name,avatarUrl','categoryModel:slug,name,icon']);

        if ($s=$r->get('q'))        $q->where(fn($w)=>$w->where('title','like',"%$s%")->orWhere('address','like',"%$s%"));
        if ($s=$r->get('city'))     $q->where('city',$s);
        if ($s=$r->get('category')) $q->where('category',$this->mapCategory($s));
        if ($s=$r->get('status'))   $q->where('status',$this->mapStatus($s));
        if ($s=$r->get('bedrooms')) $q->where('bedrooms','>=',(int)$s);
        if ($s=$r->get('bathrooms'))$q->where('bathrooms','>=',(int)$s);

        if ($r->has('minPrice') || $r->has('maxPrice')) {
            $min = (int)$r->get('minPrice', 0);
            $max = (int)$r->get('maxPrice', PHP_INT_MAX);
            $q->whereBetween('price', [$min, $max]);
        }

        // Your table has createdAt (camelCase)
        $sort = $r->get('sort');
        if      ($sort === 'price_asc')  $q->orderBy('price','asc');
        elseif  ($sort === 'price_desc') $q->orderBy('price','desc');
        else                             $q->orderBy('createdAt','desc');

        $items = $q->get()->map(function($l){
            $seller = $l->seller; // may be null if data is inconsistent
            return [
                'id'        => $l->id,
                'sellerId'  => $l->sellerId,
                'title'     => $l->title,
                'address'   => $l->address,
                'latitude'  => $l->latitude !== null ? (float)$l->latitude : null,
                'longitude' => $l->longitude !== null ? (float)$l->longitude : null,
                'city'      => $l->city,
                'price'     => (int)$l->price,
                'bedrooms'  => (int)$l->bedrooms,
                'bathrooms' => (int)$l->bathrooms,
                'areaSqm'   => (int)$l->areaSqm,
                'status'    => $this->mapStatus($l->status ?: (($l->price>700000)?'sell':'rent')),
                'category'  => $l->category ?: 'apartment',
                'categoryName' => optional($l->categoryModel)->name,
                'categoryIcon' => optional($l->categoryModel)->icon,
                'imageUrl'  => $l->imageUrl,
                'tags'      => $l->tags ? json_decode($l->tags,true) : [],
                'createdAt' => $l->createdAt,
                'seller'    => $seller
                    ? ['id'=>$seller->id,'name'=>$seller->name,'avatarUrl'=>$seller->avatarUrl,'verified'=>false]
                    : ['id'=>$l->sellerId,'name'=>null,'avatarUrl'=>null,'verified'=>false],
            ];
        });

        return response()->json($items);
    }
}
