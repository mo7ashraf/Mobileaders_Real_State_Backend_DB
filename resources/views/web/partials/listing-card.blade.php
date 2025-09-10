@props(['item'])

@php
  $img   = $item->imageUrl ?: 'https://images.unsplash.com/photo-1600585154526-990dced4db0d?q=80&w=1200&auto=format&fit=crop';
  $title = $item->title ?? '—';
  $addr  = $item->address ?? '';
  $price = number_format((int)($item->price ?? 0));
  $badg  = $item->status === 'sell' ? 'للبيع' : 'للإيجار';
@endphp

<a href="{{ route('web.listing.show',$item->id) }}"
   class="block bg-white rounded-2xl shadow hover:shadow-md transition overflow-hidden">
  <div class="aspect-[16/9] bg-gray-200">
    <img src="{{ $img }}" class="w-full h-full object-cover" onerror="this.style.display='none'">
  </div>
  <div class="p-3">
    <div class="flex items-center gap-2">
      <span class="text-xs px-2 py-1 rounded-full bg-primary/10 text-primary font-bold">{{ $badg }}</span>
      <span class="ms-auto text-primary font-bold">ر.س {{ $price }}</span>
    </div>
    <div class="mt-2 font-semibold truncate">{{ $title }}</div>
    <div class="text-gray700 text-sm truncate">{{ $addr }}</div>
    <div class="mt-2 text-xs text-gray700 flex gap-3">
      <span>🛏 {{ (int)$item->bedrooms }}</span>
      <span>🛁 {{ (int)$item->bathrooms }}</span>
      <span>📐 {{ (int)$item->areaSqm }} م²</span>
    </div>
  </div>
</a>

