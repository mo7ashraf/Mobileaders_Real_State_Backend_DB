<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $incrementing = false;
    public $timestamps   = false;         // table uses createdAt only
    protected $keyType   = 'string';
    protected $table     = 'User';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','phone','name','avatarUrl','createdAt',
        'bio','orgName','accRole','channels','socialLinks'
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
