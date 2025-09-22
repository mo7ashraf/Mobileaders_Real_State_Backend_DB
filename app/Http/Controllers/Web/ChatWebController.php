<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\ConversationParticipant;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatWebController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $convTable = (new Conversation())->getTable();
        $cpTable   = (new ConversationParticipant())->getTable();
        $msgTable  = (new Message())->getTable();

        $convs = DB::table("$convTable as c")
            ->join("$cpTable as p", 'p.conversationId', '=', 'c.id')
            ->where('p.userId', $user->id)
            ->orderBy('c.createdAt', 'desc')
            ->get(['c.id','c.title','c.createdAt']);

        $lasts = collect();
        $others = collect();
        if ($convs->count()) {
            $ids = $convs->pluck('id');
            $lasts = DB::table($msgTable)
                ->whereIn('conversationId', $ids)
                ->select('id','conversationId','body','createdAt')
                ->orderBy('createdAt', 'desc')
                ->get()
                ->groupBy('conversationId')
                ->map(fn($g) => $g->first());

            // other participant per conversation (first non-me)
            $others = DB::table("$cpTable as p2")
                ->join('user as u', 'u.id', '=', 'p2.userId')
                ->whereIn('p2.conversationId', $ids)
                ->where('p2.userId', '<>', $user->id)
                ->select(['p2.conversationId as convId','u.id','u.name','u.phone','u.avatarUrl'])
                ->get()
                ->groupBy('convId')
                ->map(fn($g) => $g->first());
        }

        // Fetch users to start a chat with (exclude me)
        $users = DB::table('user')
            ->select('id','name','phone')
            ->where('id','<>',$user->id)
            ->orderBy('name')
            ->limit(200)
            ->get();

        return view('web.chat.index', [
            'convs'  => $convs,
            'lasts'  => $lasts,
            'users'  => $users,
            'others' => $others,
        ]);
    }

    public function show(string $id)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $convTable = (new Conversation())->getTable();
        $cpTable   = (new ConversationParticipant())->getTable();
        $msgTable  = (new Message())->getTable();

        $isPart = DB::table($cpTable)
            ->where(['conversationId' => $id, 'userId' => $user->id])
            ->exists();
        if (! $isPart) {
            return redirect()->route('web.chat.index')->with('error', 'Conversation not found');
        }

        $conv = DB::table($convTable)->where('id', $id)->first();
        $messages = DB::table($msgTable)
            ->where('conversationId', $id)
            ->orderBy('createdAt')
            ->get(['id','senderId','body','createdAt']);

        return view('web.chat.show', compact('conv', 'messages', 'user'));
    }

    public function send(string $id, Request $r)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $cpTable  = (new ConversationParticipant())->getTable();
        $msgTable = (new Message())->getTable();

        $isPart = DB::table($cpTable)
            ->where(['conversationId' => $id, 'userId' => $user->id])
            ->exists();
        if (! $isPart) {
            return redirect()->route('web.chat.index')->with('error', 'Conversation not found');
        }

        $body = trim((string) $r->input('body', ''));
        if ($body === '') {
            return back()->with('error', 'Message is empty');
        }

        DB::table($msgTable)->insert([
            'id'             => (string) Str::uuid(),
            'conversationId' => $id,
            'senderId'       => $user->id,
            'body'           => $body,
            'createdAt'      => now(),
            'readAt'         => null,
        ]);

        return redirect()->route('web.chat.show', ['id' => $id]);
    }

    public function store(Request $r)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        $with = (string) $r->input('withUserId', '');
        if ($with === '') {
            return back()->with('error', 'withUserId required');
        }
        if (! DB::table('user')->where('id', $with)->exists()) {
            return back()->with('error', 'User not found');
        }

        $convTable = (new Conversation())->getTable();
        $cpTable   = (new ConversationParticipant())->getTable();

        $newId = (string) Str::uuid();
        DB::transaction(function() use ($convTable, $cpTable, $newId, $r, $user, $with) {
            DB::table($convTable)->insert([
                'id'        => $newId,
                'title'     => $r->input('title'),
                'createdAt' => now(),
            ]);
            DB::table($cpTable)->insert([
                ['conversationId' => $newId, 'userId' => $user->id],
                ['conversationId' => $newId, 'userId' => $with],
            ]);
        });

        return redirect()->route('web.chat.show', ['id' => $newId])->with('success', 'Conversation started');
    }
}
