<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model {
  public $incrementing=false; protected $keyType='string'; protected $table='User';
  protected $fillable=['id','phone','name','avatarUrl','bio','orgName','accRole','channels','socialLinks'];
  public function sellerProfile(){ return $this->hasOne(SellerProfile::class,'userId'); }
  public function listings(){ return $this->hasMany(Listing::class,'seller_id'); }
}