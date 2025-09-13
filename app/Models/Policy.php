<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Policy extends Model {
  public $timestamps=false; public $incrementing=false; protected $keyType='string'; protected $table='policy';
  protected $primaryKey='slug';
  protected $fillable=['slug','title','contentMd'];

  public function getRouteKeyName(): string { return 'slug'; }
}
