<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AppSetting extends Model {
  public $timestamps=false; public $incrementing=false; protected $keyType='int'; protected $table='appsettings';
  protected $fillable=['id','language','theme','notifications','privacy'];
}
