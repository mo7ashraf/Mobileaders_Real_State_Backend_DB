<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $table = 'Conversation';
    public $timestamps = false;
    protected $fillable = ['id','title','createdAt'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function participants() { return $this->hasMany(ConversationParticipant::class, 'conversationId'); }
    public function messages()     { return $this->hasMany(Message::class, 'conversationId')->orderBy('createdAt'); }
}
