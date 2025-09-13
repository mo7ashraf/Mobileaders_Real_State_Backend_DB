<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\PropertyRequest;
use App\Models\User;
use Illuminate\Support\Str;

class RequestController extends Controller {
  public function create(Request $r){
    // DB uses camelCase columns; ensure we use them consistently
    $u = User::orderBy('createdAt','asc')->first();
    if(!$u) return response()->json(['error'=>'no user'],400);

    $pr = new PropertyRequest();
    $pr->id         = (string) Str::uuid();
    $pr->userId     = $u->id;
    $pr->type       = $r->input('type','');
    $pr->city       = $r->input('city','');
    $pr->budgetMin  = (int)$r->input('budgetMin',0);
    $pr->budgetMax  = (int)$r->input('budgetMax',0);
    $pr->bedrooms   = (int)$r->input('bedrooms',0);
    $pr->bathrooms  = (int)$r->input('bathrooms',0);
    $pr->notes      = $r->input('notes');
    $pr->status     = 'open';
    $pr->createdAt  = now();
    $pr->save();

    return response()->json($pr,201);
  }
}

