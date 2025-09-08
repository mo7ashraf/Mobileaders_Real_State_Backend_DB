<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AppSetting;

class AppSettingsController extends Controller {
  public function get(){ $s=AppSetting::first(); if(!$s) return response()->json(['language'=>'ar','theme'=>'system','notifications'=>['all'=>true,'messages'=>true,'orders'=>true,'ads'=>true],'privacy'=>['analytics'=>true,'personalAds'=>false]]); return response()->json(['language'=>$s->language,'theme'=>$s->theme,'notifications'=>$s->notifications?json_decode($s->notifications,true):['all'=>true,'messages'=>true,'orders'=>true,'ads'=>true],'privacy'=>$s->privacy?json_decode($s->privacy,true):['analytics'=>true,'personalAds'=>false]]); }
  public function set(Request $r){ $s=AppSetting::first()?: new AppSetting(['id'=>1]); $s->language=$r->input('language',$s->language??'ar'); $s->theme=$r->input('theme',$s->theme??'system'); if($r->has('notifications')) $s->notifications=json_encode($r->input('notifications'),JSON_UNESCAPED_UNICODE); if($r->has('privacy')) $s->privacy=json_encode($r->input('privacy'),JSON_UNESCAPED_UNICODE); $s->save(); return response()->json(['ok'=>true]); }
}