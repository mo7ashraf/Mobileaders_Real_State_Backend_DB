<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SellerProfile extends Model {
  public $incrementing=false; protected $keyType='string'; protected $table='SellerProfile';
  protected $fillable=['id','userId','verified','clients','rating','badges','joinedHijri','joinedText','regionText'];
  public function user(){ return $this->belongsTo(User::class,'userId'); }
}