<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Listing;

class SellerController extends Controller
{
    public function header($id)
    {
        $u = User::with('sellerProfile','listings')->find($id);
        if (!$u) {
            return response()->json([
                'id'=>$id,'name'=>'مكتب عقاري','phone'=>'966500000000+','title'=>'وسيط عقاري','verified'=>false,
                'adsCount'=>0,'clients'=>0,'rating'=>0,
                'joinedHijri'=>'1429','joinedText'=>'أكتوبر 2021',
                'regionText'=>'الرياض • الشمال • الملقا • النرجس','badges'=>[]
            ]);
        }
        $sp = $u->sellerProfile;
        return response()->json([
            'id'=>$u->id,'name'=>$u->name,'avatarUrl'=>$u->avatarUrl ?? '','phone'=>$u->phone,
            'title'=>$u->accRole ?? 'وسيط عقاري',
            'verified'=> (bool)optional($sp)->verified,
            'adsCount'=> $u->listings->count(),
            'clients'=> (int)optional($sp)->clients,
            'rating' => (float)optional($sp)->rating,
            'joinedHijri'=> optional($sp)->joinedHijri ?: '1429',
            'joinedText' => optional($sp)->joinedText ?: 'أكتوبر 2021',
            'regionText' => optional($sp)->regionText ?: 'الرياض • الشمال • الملقا • النرجس',
            'badges'     => $sp && $sp->badges ? json_decode($sp->badges,true) : []
        ]);
    }

    public function listings($id)
    {
        $rows = Listing::where('sellerId',$id)->orderBy('createdAt','desc')->get();
        $fallbacks = [
            'https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1200&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1572120360610-d971b9d7767c?q=80&w=1200&auto=format&fit=crop',
        ];
        $out=[]; $i=0;
        foreach ($rows as $r) {
            $out[] = [
                'id'=>$r->id,'sellerId'=>$r->sellerId,'title'=>$r->title,'address'=>$r->address,'city'=>$r->city,
                'latitude'=> $r->latitude !== null ? (float)$r->latitude : null,
                'longitude'=> $r->longitude !== null ? (float)$r->longitude : null,
                'price'=>(int)$r->price,'bedrooms'=>(int)$r->bedrooms,'bathrooms'=>(int)$r->bathrooms,'areaSqm'=>(int)$r->areaSqm,
                'status'=>$r->status ?: (($r->price>700000)?'sell':'rent'),
                'category'=>$r->category ?: 'apartment',
                'imageUrl'=>$r->imageUrl ?: $fallbacks[$i % count($fallbacks)],
                'tags'=>$r->tags ? json_decode($r->tags,true): [],
                'createdAt'=>$r->createdAt,
            ];
            $i++;
        }
        return response()->json($out);
    }
    public function list()
{
    $ids = \DB::table('Listing')
        ->select('sellerId', \DB::raw('COUNT(*) as cnt'))
        ->groupBy('sellerId')
        ->orderByDesc('cnt')
        ->limit(20)
        ->pluck('sellerId')
        ->filter(fn($v) => !empty($v))
        ->values();

    $users    = \App\Models\User::whereIn('id', $ids)->get()->keyBy('id');
    $profiles = \App\Models\SellerProfile::whereIn('userId', $ids)->get()->keyBy('userId');

    $lastCities = \DB::table('Listing')
        ->select('sellerId', \DB::raw('MAX(createdAt) as latest'))
        ->whereIn('sellerId', $ids)
        ->groupBy('sellerId');

    $cities = \DB::table('Listing as l')
        ->joinSub($lastCities, 't', function($j){ $j->on('l.sellerId','=','t.sellerId')->on('l.createdAt','=','t.latest'); })
        ->pluck('l.city','l.sellerId');

    $out = [];
    foreach ($ids as $id) {
        if (!$users->has($id)) continue;
        $u = $users[$id];
        $p = $profiles->get($id);
        $out[] = [
            'id'        => (string)$u->id,
            'name'      => $u->name,
            'avatarUrl' => $u->avatarUrl,
            'verified'  => (bool)($p->verified ?? false),
            'phone'     => $u->phone,
            'bio'       => $u->bio ?? 'وسيط عقاري',
            'city'      => $cities[$id] ?? 'الرياض',
        ];
    }
    return response()->json($out);
}
}
