<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ConversationsController extends Controller
{
    private function currentUser(): User
    {
        // Prefer authenticated user if you enable Sanctum later
        $u = auth()->user();
        if ($u instanceof User) return $u;

        // fallback to your existing "first user" behavior
        $u = User::orderBy('createdAt','asc')->first();
        if ($u) return $u;

        // create seed user if empty DB
        $u = new User();
        $u->id        = (string) Str::uuid();
        $u->phone     = '+966500000000';
        $u->name      = 'مستخدم';
        $u->createdAt = now();
        $u->save();
        return $u;
    }

    // GET /api/conversations
    public function index()
    {
        $me = $this->currentUser();
       $conv = (new Conversation)->getTable();                 // "conversation"
        $cp   = (new ConversationParticipant)->getTable();      // "conversationparticipant"

       $convs = Conversation::query()
           ->select("$conv.id","$conv.title","$conv.createdAt")
            ->join("$cp as cp",'cp.conversationId',"=","$conv.id")
            ->where('cp.userId',$me->id)
            ->orderByDesc("$conv.createdAt")
            ->get()
            ->map(function($c){
                // last message preview
                $last = Message::where('conversationId',$c->id)->orderByDesc('createdAt')->first();
                return [
                    'id'    => $c->id,
                    'title' => $c->title ?? 'محادثة',
                    'last'  => $last? ['body'=>$last->body,'createdAt'=>$last->createdAt] : null,
                ];
            });

        return response()->json($convs);
    }

    // POST /api/conversations  { "withUserId": "...", "title": "..." }
    public function store(Request $r)
    {
        $me   = $this->currentUser();
        $with = $r->string('withUserId')->toString();

        if (!$with) return response()->json(['ok'=>false,'error'=>'withUserId required'],422);
        if (!User::where('id',$with)->exists()) return response()->json(['ok'=>false,'error'=>'user not found'],404);

        $convId = (string) Str::uuid();
        DB::transaction(function() use ($convId,$r,$me,$with){
            Conversation::create(['id'=>$convId,'title'=>$r->input('title'),'createdAt'=>now()]);
            ConversationParticipant::create(['conversationId'=>$convId,'userId'=>$me->id]);
            ConversationParticipant::create(['conversationId'=>$convId,'userId'=>$with]);
        });

        return response()->json(['ok'=>true,'id'=>$convId]);
    }

    // GET /api/conversations/{id}/messages
    public function messages($id)
    {
        $me = $this->currentUser();

        $isParticipant = ConversationParticipant::where('conversationId',$id)->where('userId',$me->id)->exists();
        if (!$isParticipant) return response()->json(['error'=>'forbidden'],403);

        $items = Message::where('conversationId',$id)->orderBy('createdAt')->get([
            'id','senderId','body','createdAt','readAt'
        ])->map(function($m) use ($me){
            return [
                'id'        => $m->id,
                'from'      => $m->senderId === $me->id ? 'me' : 'other',
                'senderId'  => $m->senderId,
                'body'      => $m->body,
                'createdAt' => $m->createdAt,
                'readAt'    => $m->readAt,
            ];
        });

        return response()->json($items);
    }

    // POST /api/conversations/{id}/messages  { "body": "..." }
    public function send($id, Request $r)
    {
        $me = $this->currentUser();
        $body = trim((string)$r->input('body',''));
        if ($body === '') return response()->json(['ok'=>false,'error'=>'empty body'],422);

        $isParticipant = ConversationParticipant::where('conversationId',$id)->where('userId',$me->id)->exists();
        if (!$isParticipant) return response()->json(['error'=>'forbidden'],403);

        $msg = Message::create([
            'id'            => (string) Str::uuid(),
            'conversationId'=> $id,
            'senderId'      => $me->id,
            'body'          => $body,
            'createdAt'     => now(),
        ]);

        return response()->json(['ok'=>true,'messageId'=>$msg->id]);
    }
}
