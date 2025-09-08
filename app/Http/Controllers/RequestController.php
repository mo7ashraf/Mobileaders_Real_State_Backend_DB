<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PropertyRequest;
use App\Models\User;

class RequestController extends Controller {
  public function create(Request $r){
    $u=User::orderBy('created_at','asc')->first(); if(!$u) return response()->json(['error'=>'no user'],400);
    $pr=new PropertyRequest(); $pr->user_id=$u->id; $pr->type=$r->input('type','شقة'); $pr->city=$r->input('city','الرياض');
    $pr->budget_min=(int)$r->input('budgetMin',0); $pr->budget_max=(int)$r->input('budgetMax',0);
    $pr->bedrooms=(int)$r->input('bedrooms',0); $pr->bathrooms=(int)$r->input('bathrooms',0);
    $pr->notes=$r->input('notes'); $pr->status='open'; $pr->save(); return response()->json($pr,201);
  }
}