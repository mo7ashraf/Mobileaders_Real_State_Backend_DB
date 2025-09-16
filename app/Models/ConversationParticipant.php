<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationParticipant extends Model
{
    protected $table = 'ConversationParticipant';
    public $timestamps = false;
    protected $fillable = ['conversationId','userId','joinedAt'];
    public $incrementing = false;
    protected $primaryKey = ['conversationId','userId'];
}
