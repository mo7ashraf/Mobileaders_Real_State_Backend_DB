@extends('web.layout')
@section('title','Chat')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-xl font-bold">Chat</h1>
    <form method="post" action="{{ route('web.chat.store') }}" class="flex items-center gap-2">
      @csrf
      <select name="withUserId" class="border rounded-lg px-3 py-2 min-w-[240px]" required>
        <option value="" selected disabled>Select user…</option>
        @foreach($users as $u)
          <option value="{{ $u->id }}">{{ $u->name ?: $u->phone }} ({{ $u->id }})</option>
        @endforeach
      </select>
      <input name="title" class="border rounded-lg px-3 py-2" placeholder="Title (optional)">
      <button class="px-4 py-2 bg-primary text-white rounded-xl">Start</button>
    </form>
  </div>

  @if($convs->isEmpty())
    <div class="bg-white rounded-2xl shadow p-6 text-gray700">No conversations yet.</div>
  @else
    <div class="bg-white rounded-2xl shadow divide-y">
      @foreach ($convs as $c)
        @php($last = $lasts[$c->id] ?? null)
        @php($other = $others[$c->id] ?? null)
        <a href="{{ route('web.chat.show', ['id'=>$c->id]) }}" class="block px-4 py-3 hover:bg-gray-50">
          <div class="flex items-center justify-between">
            <div class="font-semibold">{{ $c->title ?: 'Conversation' }}</div>
            <div class="text-xs text-gray500">
              {{ \Carbon\Carbon::parse($last->createdAt ?? $c->createdAt)->diffForHumans() }}
            </div>
          </div>
          @if($other)
            <div class="text-xs text-gray500">with: {{ $other->name ?: $other->phone }}</div>
          @endif
          @if($last)
            <div class="text-sm text-gray700 line-clamp-1">{{ $last->body }}</div>
          @else
            <div class="text-sm text-gray500">No messages</div>
          @endif
        </a>
      @endforeach
    </div>
  @endif
@endsection
