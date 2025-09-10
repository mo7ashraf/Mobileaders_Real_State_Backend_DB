<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public $incrementing = false;
    public $timestamps   = false;           // table uses createdAt only
    protected $keyType   = 'string';
    protected $table     = 'Listing';
    protected $primaryKey = 'id';

    // match your camelCase columns
    protected $fillable = [
        'id','sellerId','title','address','city','price',
        'bedrooms','bathrooms','areaSqm','status','category',
        'imageUrl','tags','createdAt',
    ];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    public function seller()
    {
        // FK = sellerId, owner key = id
        return $this->belongsTo(User::class, 'sellerId', 'id');
    }
}
