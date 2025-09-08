<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Listing extends Model {
  public $incrementing=false; protected $keyType='string'; protected $table='Listing';
  protected $fillable=['id','seller_id','title','address','city','price','bedrooms','bathrooms','area_sqm','status','category','image_url','tags'];
  public function seller(){ return $this->belongsTo(User::class,'seller_id'); }
}