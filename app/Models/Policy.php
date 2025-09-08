<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Policy extends Model {
  public $timestamps=false; public $incrementing=false; protected $keyType='string'; protected $table='Policy';
  protected $fillable=['slug','title','contentMd'];
}