<?php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Listing;

class SellerController extends Controller {
  public function header($id){
    $u=User::with('sellerProfile','listings')->find($id);
    if(!$u) return response()->json(['id'=>$id,'name'=>'مكتب عقاري','phone'=>'966500000000+','title'=>'وسيط عقاري','verified'=>false,'adsCount'=>0,'clients'=>0,'rating'=>0,'joinedHijri'=>'1429','joinedText'=>'أكتوبر 2021','regionText'=>'الرياض • الشمال • الملقا • النرجس','badges'=>[]]);
    $sp=$u->sellerProfile;
    return response()->json(['id'=>$u->id,'name'=>$u->name,'avatarUrl'=>$u->avatarUrl??'','phone'=>$u->phone,'title'=>$u->accRole??'وسيط عقاري','verified'=>(bool)optional($sp)->verified,'adsCount'=>$u->listings->count(),'clients'=>(int)optional($sp)->clients,'rating'=>(float)optional($sp)->rating,'joinedHijri'=>optional($sp)->joinedHijri?:'1429','joinedText'=>optional($sp)->joinedText?:'أكتوبر 2021','regionText'=>optional($sp)->regionText?:'الرياض • الشمال • الملقا • النرجس','badges'=>$sp&&$sp->badges?json_decode($sp->badges,true):[]]);
  }
  public function listings($id){
    $rows=Listing::where('seller_id',$id)->orderBy('created_at','desc')->get();
    $f=['https://images.unsplash.com/photo-1560518883-ce09059eeffa?q=80&w=1200&auto=format&fit=crop','https://images.unsplash.com/photo-1570129477492-45c003edd2be?q=80&w=1200&auto=format&fit=crop','https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1200&auto=format&fit=crop','https://images.unsplash.com/photo-1572120360610-d971b9d7767c?q=80&w=1200&auto=format&fit=crop'];
    $out=[];$i=0; foreach($rows as $r){ $out[]=['id'=>$r->id,'sellerId'=>$r->seller_id,'title'=>$r->title,'address'=>$r->address,'city'=>$r->city,'price'=>$r->price,'bedrooms'=>$r->bedrooms,'bathrooms'=>$r->bathrooms,'areaSqm'=>$r->area_sqm,'status'=>$r->status ?: (($r->price>700000)?'sell':'rent'),'category'=>$r->category ?: 'apartment','imageUrl'=>$r->image_url ?: $f[$i % count($f)],'tags'=>$r->tags?json_decode($r->tags,true):[],'createdAt'=>$r->created_at]; $i++; }
    return response()->json($out);
  }
}