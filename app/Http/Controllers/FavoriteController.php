<?php
namespace App\Http\Controllers;
use App\Models\Favorite;
use App\Models\Listing;
use App\Models\User;

class FavoriteController extends Controller {
  private function uid(){ return optional(User::orderBy('createdAt','asc')->first())->id; }

  // DB columns are camelCase (userId, listingId) on table `Favorite`
  public function add($listingId){
    $uid = $this->uid();
    if(!$uid) return response()->json(['error'=>'no user'],400);
    Favorite::firstOrCreate(['userId'=>$uid,'listingId'=>$listingId]);
    return response()->json(['ok'=>true]);
  }

  public function remove($listingId){
    $uid = $this->uid();
    if(!$uid) return response()->json(['error'=>'no user'],400);
    Favorite::where('userId',$uid)->where('listingId',$listingId)->delete();
    return response()->json(['ok'=>true]);
  }

  public function list(){
    $uid = $this->uid();
    if(!$uid) return response()->json([]);
    $ids = Favorite::where('userId',$uid)->pluck('listingId');
    return response()->json(Listing::whereIn('id',$ids)->get());
  }
}
