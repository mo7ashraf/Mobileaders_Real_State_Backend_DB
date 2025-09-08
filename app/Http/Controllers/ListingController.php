<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Listing;

class ListingController extends Controller {
  private function mapCategory($cat){ $m=['شقة'=>'apartment','شقق'=>'apartment','apartment'=>'apartment','فيلا'=>'villa','فلل'=>'villa','villa'=>'villa','office'=>'office','مكتب'=>'office','مكاتب'=>'office','استراحة'=>'resthouse','استراحات'=>'resthouse','resthouse'=>'resthouse']; $s=mb_strtolower(trim((string)$cat)); return $m[$s]??$cat; }
  private function mapStatus($st){ $s=mb_strtolower(trim((string)$st)); if(in_array($s,['rent','للإيجار'])) return 'rent'; if(in_array($s,['sell','للبيع'])) return 'sell'; return $st; }

  public function index(Request $r){
    $q = Listing::query()->with(['seller:id,name,avatarUrl']);
    if($s=$r->get('q')) $q->where(fn($w)=>$w->where('title','like',"%$s%")->orWhere('address','like',"%$s%"));
    if($s=$r->get('city')) $q->where('city',$s);
    if($s=$r->get('category')) $q->where('category',$this->mapCategory($s));
    if($s=$r->get('status')) $q->where('status',$this->mapStatus($s));
    if($s=$r->get('bedrooms')) $q->where('bedrooms','>=',(int)$s);
    if($s=$r->get('bathrooms')) $q->where('bathrooms','>=',(int)$s);
    if($r->has('minPrice')||$r->has('maxPrice')){ $min=(int)$r->get('minPrice',0); $max=(int)$r->get('maxPrice',PHP_INT_MAX); $q->whereBetween('price',[$min,$max]); }
    $sort=$r->get('sort'); if($sort==='price_asc') $q->orderBy('price','asc'); else if($sort==='price_desc') $q->orderBy('price','desc'); else $q->orderBy('created_at','desc');
    $items=$q->get()->map(function($l){ return [
      'id'=>$l->id,'sellerId'=>$l->seller_id,'title'=>$l->title,'address'=>$l->address,'city'=>$l->city,
      'price'=>$l->price,'bedrooms'=>$l->bedrooms,'bathrooms'=>$l->bathrooms,'areaSqm'=>$l->area_sqm,
      'status'=>$l->status ?: (($l->price>700000)?'sell':'rent'),'category'=>$l->category ?: 'apartment',
      'imageUrl'=>$l->image_url,'tags'=>$l->tags?json_decode($l->tags,true):[],'createdAt'=>$l->created_at,
      'seller'=>['id'=>$l->seller->id,'name'=>$l->seller->name,'avatarUrl'=>$l->seller->avatarUrl,'verified'=>false]
    ];});
    return response()->json($items);
  }
}