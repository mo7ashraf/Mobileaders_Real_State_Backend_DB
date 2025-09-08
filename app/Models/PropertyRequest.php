<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PropertyRequest extends Model {
  public $incrementing=false; protected $keyType='string'; protected $table='PropertyRequest';
  protected $fillable=['id','user_id','type','city','budget_min','budget_max','bedrooms','bathrooms','notes','status'];
}