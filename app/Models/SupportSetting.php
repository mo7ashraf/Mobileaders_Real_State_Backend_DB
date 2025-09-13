<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SupportSetting extends Model {
  public $timestamps=false; public $incrementing=false; protected $keyType='int'; protected $table='supportsettings';
  protected $fillable=['id','whatsapp','email'];
}
