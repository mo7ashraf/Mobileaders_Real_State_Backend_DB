<?php
namespace App\Http\Controllers;
use App\Models\Favorite;
use App\Models\Listing;
use App\Models\User;

class FavoriteController extends Controller {
  private function uid(){ return optional(User::orderBy('created_at','asc')->first())->id; }
  public function add($listingId){ $uid=$this->uid(); if(!$uid) return response()->json(['error'=>'no user'],400); Favorite::firstOrCreate(['user_id'=>$uid,'listing_id'=>$listingId]); return response()->json(['ok'=>true]); }
  public function remove($listingId){ $uid=$this->uid(); if(!$uid) return response()->json(['error'=>'no user'],400); Favorite::where('user_id',$uid)->where('listing_id',$listingId)->delete(); return response()->json(['ok'=>true]); }
  public function list(){ $uid=$this->uid(); if(!$uid) return response()->json([]); $ids=Favorite::where('user_id',$uid)->pluck('listing_id'); return response()->json(Listing::whereIn('id',$ids)->get()); }
}