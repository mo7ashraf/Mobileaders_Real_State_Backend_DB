<?php
namespace App\Http\Controllers;
use App\Models\Policy;

class PolicyController extends Controller {
  public function get($slug){ $p=Policy::where('slug',$slug)->first(); if(!$p) return response()->json(['error'=>'not found'],404); return response()->json(['slug'=>$slug,'title'=>$p->title,'content'=>$p->contentMd]); }
}