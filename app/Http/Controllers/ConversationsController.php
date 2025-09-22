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
    /*public function index()
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
    }*/

    

    // GET /api/conversations/{id}/messages
   /* public function messages($id)
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
    }*/

    
    // GET /api/conversations  (?me=u1 for testing)
    public function index()
{
    $me = $this->currentUser();
    if (!$me) return response()->json([]);

    $createdC = $this->col('conversation', 'createdAt', 'created_at');

    $convs = DB::table('conversation as c')
        ->join('conversationparticipant as cp', 'cp.conversationId', '=', 'c.id') // change to snake if your cp FKs are snake
        ->where('cp.userId', $me->id)
        ->orderBy("c.$createdC", 'desc')
        ->get(["c.id", "c.title", "c.$createdC as createdAt"]);

    $createdM = $this->col('message', 'createdAt', 'created_at');

    $out = $convs->map(function ($c) use ($createdM, $me) {
        $last = DB::table('message')
            ->where('conversationId', $c->id)
            ->orderBy($createdM, 'desc')
            ->first();

        // NEW: other participant info
        $other = $this->otherParticipant($c->id, $me->id);

        return [
            'id'    => $c->id,
            'title' => $c->title ?? 'محادثة',
            'with'  => $other ? [
                'id'        => $other->id,
                'name'      => $other->name,
                'avatarUrl' => $other->avatarUrl,
                'phone'     => $other->phone,
            ] : null,
            'last'  => $last ? ['body' => $last->body, 'createdAt' => $last->$createdM] : null,
        ];
    });

    return response()->json($out);
}

    // GET /api/conversations/{id}/messages  (?me=u1)
    public function messages(string $id, Request $r)
{
    $me = $this->currentUser();
    if (!$me) return response()->json([]);

    // membership check
    $cpConvCol = $this->col('conversationparticipant', 'conversationId', 'conversation_id');
    $cpUserCol = $this->col('conversationparticipant', 'userId', 'user_id');

    $isPart = DB::table('conversationparticipant')
        ->where([$cpConvCol => $id, $cpUserCol => $me->id])
        ->exists();
    if (!$isPart) return response()->json(['error' => 'forbidden'], 403);

    // detect message columns
    $msgTable = 'message';
    $createdM = $this->col($msgTable, 'createdAt', 'created_at');
    $readM    = $this->col($msgTable, 'readAt', 'read_at');
    $senderM  = $this->col($msgTable, 'senderId', 'sender_id');
    $convFK   = $this->col($msgTable, 'conversationId', 'conversation_id');

    // other participant (for header)
    $other = $this->otherParticipant($id, $me->id);

    // fetch messages
    $rows = DB::table($msgTable)
        ->select(['id', "$senderM as senderId", 'body', "$createdM as createdAt", "$readM as readAt"])
        ->where($convFK, $id)
        ->orderBy($createdM)
        ->get();

    // build response
    $items = $rows->map(function ($m) use ($me, $other) {
        $fromMe = $m->senderId === $me->id;
        return [
            'id'         => $m->id,
            'from'       => $fromMe ? 'me' : 'other',
            'senderId'   => $m->senderId,
            'senderName' => $fromMe ? ($me->name ?? 'أنا') : ($other->name ?? 'الطرف الآخر'),
            'body'       => $m->body,
            'createdAt'  => $m->createdAt,
            'readAt'     => $m->readAt,
        ];
    });

    return response()->json([
        'with'  => $other ? [
            'id'        => $other->id,
            'name'      => $other->name,
            'avatarUrl' => $other->avatarUrl,
            'phone'     => $other->phone,
        ] : null,
        'items' => $items,
    ]);
}


    /*public function messages(string $id, Request $r)
    {
        $me = $this->currentUser();
        if (!$me) return response()->json([]);

        $isPart = DB::table('conversationparticipant')
            ->where(['conversationId' => $id, 'userId' => $me->id])->exists();
        if (!$isPart) return response()->json(['error' => 'forbidden'], 403);

        $createdM = $this->col('message', 'createdAt', 'created_at');
        $readM    = $this->col('message', 'readAt', 'read_at');

        $rows = DB::table('message')
            ->select(['id', 'senderId', 'body', "$createdM as createdAt", "$readM as readAt"])
            ->where('conversationId', $id)
            ->orderBy($createdM)
            ->get();

        return response()->json($rows->map(function ($m) use ($me) {
            return [
                'id'        => $m->id,
                'from'      => $m->senderId === $me->id ? 'me' : 'other',
                'senderId'  => $m->senderId,
                'body'      => $m->body,
                'createdAt' => $m->createdAt,
                'readAt'    => $m->readAt,
            ];
        }));
    }*/
// POST /api/conversations/{id}/messages  { "body": "..." }
    /*public function send($id, Request $r)
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
    }*/
    // POST /api/conversations/{id}/messages  { body }  (?me=u1)
    public function send(string $id, Request $r)
{
    $me = $this->currentUser();
    if (!$me) return response()->json(['ok' => false, 'error' => 'no users'], 500);

    $isPart = DB::table('conversationparticipant')
        ->where(['conversationId' => $id, 'userId' => $me->id]) // change to snake if needed
        ->exists();
    if (!$isPart) return response()->json(['error' => 'forbidden'], 403);

    $body = trim((string)$r->input('body', ''));
    if ($body === '') return response()->json(['ok' => false, 'error' => 'empty body'], 422);

    $createdM = $this->col('message',         'createdAt',       'created_at');
    $readM    = $this->col('message',         'readAt',          'read_at');
    $convFK   = $this->col('message',         'conversationId',  'conversation_id');  // NEW
    $senderFK = $this->col('message',         'senderId',        'sender_id');        // NEW

    DB::table('message')->insert([
        'id'      => (string) Str::uuid(),
        $convFK   => $id,
        $senderFK => $me->id,
        'body'    => $body,
        $createdM => now(),
        $readM    => null,
    ]);

    return response()->json(['ok' => true]);
}
   /* public function send(string $id, Request $r)
    {
        $me = $this->currentUser();
        if (!$me) return response()->json(['ok' => false, 'error' => 'no users'], 500);

        $isPart = DB::table('conversationparticipant')
            ->where(['conversationId' => $id, 'userId' => $me->id])->exists();
        if (!$isPart) return response()->json(['error' => 'forbidden'], 403);

        $body = trim((string)$r->input('body', ''));
        if ($body === '') return response()->json(['ok' => false, 'error' => 'empty body'], 422);

        $createdM = $this->col('message', 'createdAt', 'created_at');
        $readM    = $this->col('message', 'readAt', 'read_at');

        DB::table('message')->insert([
            'id'             => (string) Str::uuid(),
            'conversationId' => $id,     // change to conversation_id if your FK is snake
            'senderId'       => $me->id, // or sender_id if snake
            'body'           => $body,
            $createdM        => now(),
            $readM           => null,
        ]);

        return response()->json(['ok' => true]);
    }*/
// POST /api/conversations  { "withUserId": "...", "title": "..." }
    /*public function store(Request $r)
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
    }*/
    // POST /api/conversations  { withUserId, title? }  (?me=u1)
    public function store(Request $r)
    {
        $me   = $this->currentUser();
        if (!$me) return response()->json(['ok' => false, 'error' => 'no users'], 500);

        $with = (string) $r->input('withUserId', '');
        if ($with === '') return response()->json(['ok' => false, 'error' => 'withUserId required'], 422);
        if (!DB::table('user')->where('id', $with)->exists())
            return response()->json(['ok' => false, 'error' => 'user not found'], 404);

        $createdC = $this->col('conversation', 'createdAt', 'created_at');
        $id = (string) Str::uuid();

        DB::table('conversation')->insert([
            'id'        => $id,
            'title'     => $r->input('title'),
            $createdC   => now(),
        ]);

        DB::table('conversationparticipant')->insert([
            ['conversationId' => $id, 'userId' => $me->id],
            ['conversationId' => $id, 'userId' => $with],
        ]);

        return response()->json(['ok' => true, 'id' => $id]);
    }
    /** Return the other participant (id, name, avatarUrl, phone) for a conversation */
private function otherParticipant(string $conversationId, string $meId): ?object
{
    $cpUserCol = $this->col('conversationparticipant', 'userId', 'user_id');
    $cpConvCol = $this->col('conversationparticipant', 'conversationId', 'conversation_id');

    return DB::table('conversationparticipant as cp')
        ->join('user as u', 'u.id', '=', DB::raw("cp.`$cpUserCol`"))
        ->where("cp.$cpConvCol", $conversationId)
        ->where("cp.$cpUserCol", '<>', $meId)
        ->select(['u.id','u.name','u.avatarUrl','u.phone'])
        ->first();
}

    protected function col(string $table, string $camel, string $snake): string
    {
        static $cols = [];
        if (!isset($cols[$table])) {
            $cols[$table] = array_map(fn($r) => $r->Field, DB::select("SHOW COLUMNS FROM `$table`"));
        }
        $has = $cols[$table];
        if (in_array($camel, $has, true)) return $camel;
        if (in_array($snake, $has, true)) return $snake;
        return $camel; // visible failure if neither exists
    }

}
