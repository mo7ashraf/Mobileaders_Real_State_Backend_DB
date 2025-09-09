<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller {
  public function index(Request $r){ $star=$r->boolean('starred',false); $q=Notification::query(); if($star) $q->where('starred',true); return response()->json($q->orderBy('createdAt','desc')->get()); }
  public function star($id){ $n=Notification::find($id); if(!$n){ $n=new Notification(['id'=>$id,'title'=>'إشعار','starred'=>true]); $n->save(); return response()->json(['ok'=>true,'starred'=>true]); } $n->starred=!$n->starred; $n->save(); return response()->json(['ok'=>true,'starred'=>$n->starred]); }
  public function readAll(){ Notification::whereNull('readAt')->update(['readAt'=>now()]); return response()->json(['ok'=>true]); }
}