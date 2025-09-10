@extends('web.layout')
@section('title', $seller->name ?? 'الملف')

@section('content')
  <div class="bg-white rounded-2xl shadow p-4 mb-6">
    <div class="flex items-center gap-3">
      <img src="{{ $seller->avatarUrl ?? '' }}" class="w-16 h-16 rounded-full object-cover" onerror="this.style.display='none'">
      <div class="flex-1">
        <div class="flex items-center gap-2">
          <h1 class="font-bold text-lg truncate">{{ $seller->name }}</h1>
          @if(optional($profile)->verified) <span class="text-primary">✔</span> @endif
        </div>
        <div class="text-gray700">{{ $seller->accRole ?? 'وسيط عقاري' }}</div>
        <div class="text-gray700 text-sm">{{ optional($profile)->regionText ?? '—' }}</div>
      </div>
    </div>
    <div class="mt-3 flex gap-6 text-sm">
      <div>إعلان: <b>{{ $listings->total() }}</b></div>
      <div>عميل: <b>{{ (int)optional($profile)->clients }}</b></div>
      <div>تقييم: <b>{{ (float)optional($profile)->rating }}</b></div>
    </div>
  </div>

  <h2 class="font-bold mb-3">الإعلانات</h2>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
    @foreach($listings as $it)
      @include('web.partials.listing-card',['item'=>$it])
    @endforeach
  </div>
  {{ $listings->links() }}
@endsection

