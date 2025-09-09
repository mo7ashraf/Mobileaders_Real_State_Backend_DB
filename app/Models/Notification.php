<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Notification extends Model {
  public $incrementing = false;
  public $timestamps   = false;
  protected $keyType   = 'string';
  protected $table     = 'Notification';
  protected $fillable  = ['id','userId','title','subtitle','starred','readAt','createdAt'];
}