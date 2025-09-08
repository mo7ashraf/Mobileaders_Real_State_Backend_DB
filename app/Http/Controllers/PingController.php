<?php
namespace App\Http\Controllers;
class PingController extends Controller { public function ok(){ return response()->json(['ok'=>true]); } }