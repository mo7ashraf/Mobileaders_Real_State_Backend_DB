<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
  public $incrementing=false; protected $keyType='string'; protected $table='`Order`';
  protected $fillable=['id','user_id','status','notes'];
}