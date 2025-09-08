<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Notification extends Model {
  public $incrementing=false; protected $keyType='string'; protected $table='Notification';
  protected $fillable=['id','user_id','title','subtitle','starred','readAt'];
}