<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Favorite extends Model {
  public $timestamps=false; protected $table='Favorite'; protected $fillable=['user_id','listing_id'];
}