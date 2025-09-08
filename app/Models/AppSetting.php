<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AppSetting extends Model {
  public $timestamps=false; public $incrementing=false; protected $keyType='int'; protected $table='AppSettings';
  protected $fillable=['id','language','theme','notifications','privacy'];
}