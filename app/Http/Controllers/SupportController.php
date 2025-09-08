<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SupportSetting;

class SupportController extends Controller {
  public function get(){ $s=SupportSetting::first(); if(!$s) return response()->json(['whatsapp'=>'966500000000','email'=>'support@example.com']); return response()->json(['whatsapp'=>$s->whatsapp,'email'=>$s->email]); }
  public function set(Request $r){ $s=SupportSetting::first()?: new SupportSetting(['id'=>1]); $s->whatsapp=$r->input('whatsapp','966500000000'); $s->email=$r->input('email','support@example.com'); $s->save(); return response()->json(['ok'=>true]); }
}