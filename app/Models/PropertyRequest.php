<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PropertyRequest extends Model {
  public $incrementing = false;
  public $timestamps   = false;
  protected $keyType   = 'string';
  protected $table     = 'PropertyRequest';
  protected $fillable  = ['id','userId','type','city','budgetMin','budgetMax','bedrooms','bathrooms','notes','status','createdAt'];

  protected $casts = [
    'createdAt' => 'datetime',
  ];
}
