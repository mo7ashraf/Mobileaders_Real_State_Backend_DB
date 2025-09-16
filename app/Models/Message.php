<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'Message';
    public $timestamps = false;
    protected $fillable = ['id','conversationId','senderId','body','createdAt','readAt'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function conversation(){ return $this->belongsTo(Conversation::class, 'conversationId'); }
    public function sender(){ return $this->belongsTo(User::class, 'senderId'); }
}
