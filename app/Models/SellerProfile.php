<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerProfile extends Model
{
    public $incrementing = false;
    public $timestamps   = false;
    protected $keyType   = 'string';
    protected $table     = 'sellerprofile';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id','userId','verified','clients','rating','badges',
        'joinedHijri','joinedText','regionText'
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId', 'id');
    }
}
