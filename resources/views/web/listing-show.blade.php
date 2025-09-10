@extends('web.layout')
@section('title', $item->title ?? 'تفاصيل الإعلان')

@section('content')
  <div class="bg-white rounded-2xl overflow-hidden shadow mb-6">
    <div class="aspect-[16/9] bg-gray-200">
      <img src="{{ $item->imageUrl ?: 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1200&auto=format&fit=crop' }}"
           class="w-full h-full object-cover">
    </div>
    <div class="p-4">
      <div class="flex items-center gap-2">
        <h1 class="font-bold text-lg truncate">{{ $item->title }}</h1>
        <span class="ms-auto text-primary font-bold">ر.س {{ number_format((int)$item->price) }}</span>
      </div>
      <div class="mt-1 text-gray700 text-sm truncate">📍 {{ $item->address }}</div>

      <div class="mt-3 flex gap-3 text-sm">
        <span class="px-3 py-1 bg-primary/10 text-primary rounded-full">{{ $item->status === 'sell' ? 'للبيع' : 'للإيجار' }}</span>
        <span>🛏 {{ (int)$item->bedrooms }}</span>
        <span>🛁 {{ (int)$item->bathrooms }}</span>
        <span>📐 {{ (int)$item->areaSqm }} م²</span>
      </div>
    </div>
  </div>

  @if($seller)
    <div class="bg-white rounded-2xl shadow p-4 mb-6">
      <div class="flex items-center gap-3">
        <img src="{{ $seller->avatarUrl ?? '' }}" class="w-12 h-12 rounded-full object-cover" onerror="this.style.display='none'">
        <div class="flex-1">
          <div class="font-semibold">{{ $seller->name }}</div>
          <div class="text-gray700 text-sm">{{ $seller->accRole ?? 'وسيط عقاري' }}</div>
        </div>
        <a href="{{ route('web.seller.show',$seller->id) }}" class="px-3 py-2 bg-primary text-white rounded-xl">محادثة</a>
      </div>
    </div>
  @endif

  <h2 class="font-bold mb-3">عقارات مشابهة</h2>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($similar as $it)
      @include('web.partials.listing-card',['item'=>$it])
    @endforeach
  </div>
@endsection
