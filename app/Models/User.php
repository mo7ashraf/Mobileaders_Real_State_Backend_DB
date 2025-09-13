<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    public $incrementing = false;
    public $timestamps   = false;         // table uses createdAt only
    protected $keyType   = 'string';
    protected $table     = 'user';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','phone','name','avatarUrl','createdAt',
        'bio','orgName','accRole','channels','socialLinks'
    ];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    public function sellerProfile()
    {
        return $this->hasOne(SellerProfile::class, 'userId', 'id');
    }

    public function listings()
    {
        return $this->hasMany(Listing::class, 'sellerId', 'id');
    }
}
