<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;

class OrderController extends Controller {
  public function index(){ return response()->json(Order::orderBy('created_at','desc')->get()); }
  public function show($id){ $o=Order::find($id); if(!$o) return response()->json(['error'=>'not found'],404); return response()->json($o); }
  public function create(Request $r){ $u=User::orderBy('created_at','asc')->first(); if(!$u) return response()->json(['error'=>'no user'],400); $o=new Order(); $o->user_id=$u->id; $o->status='open'; $o->notes=$r->input('notes'); $o->save(); return response()->json($o,201); }
}