<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Order extends Model {
  public $incrementing = false;
  public $timestamps   = false;
  protected $keyType   = 'string';
  protected $table     = 'Order';
  protected $fillable  = ['id','userId','status','notes','createdAt'];

  protected $casts = [
    'createdAt' => 'datetime',
  ];
}
