@extends('web.layout')
@section('title', $conv->title ? ('Chat · '.$conv->title) : 'Chat')

@section('content')
  <div class="flex gap-6">
    <div class="flex-1">
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="mb-4">
          <a href="{{ route('web.chat.index') }}" class="text-sm text-gray500 hover:text-primary">← Back</a>
          <h1 class="text-xl font-bold mt-1">{{ $conv->title ?: 'Conversation' }}</h1>
        </div>

        <div class="space-y-3 max-h-[60vh] overflow-y-auto pr-2" id="messages">
          @forelse ($messages as $m)
            @php($isMe = $m->senderId === $user->id)
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
              <div class="max-w-[75%] {{ $isMe ? 'bg-primary text-white' : 'bg-gray-100 text-gray900' }} rounded-2xl px-4 py-2">
                <div class="text-sm whitespace-pre-wrap">{{ $m->body }}</div>
                <div class="text-[10px] mt-1 opacity-80 text-right">
                  {{ \Carbon\Carbon::parse($m->createdAt)->format('Y-m-d H:i') }}
                </div>
              </div>
            </div>
          @empty
            <div class="text-gray500 text-sm">No messages yet.</div>
          @endforelse
        </div>

        <form method="post" action="{{ route('web.chat.send', ['id'=>$conv->id]) }}" class="mt-4 flex items-end gap-2">
          @csrf
          <textarea name="body" class="flex-1 border rounded-xl px-3 py-2" rows="2" placeholder="Type a message..." required></textarea>
          <button class="px-4 py-2 bg-primary text-white rounded-xl">Send</button>
        </form>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
<script>
  // Auto-scroll to bottom on load
  const box = document.getElementById('messages');
  if (box) box.scrollTop = box.scrollHeight;
</script>
@endpush

