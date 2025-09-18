@extends('web.layout')
@section('title','الصفحة الرئيسية')

@section('content')
  <div class="bg-white rounded-2xl p-4 mb-6 flex items-center justify-between">
    <div>
      <div class="font-bold text-lg">أضف طلب عقاري</div>
      <div class="text-gray700 text-sm">سنساعدك للوصول إلى الخيار المناسب</div>
    </div>
    <a href="{{ route('web.search') }}" class="px-4 py-2 bg-primary text-white rounded-xl">إرسال طلب</a>
  </div>

  <div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">الإعلانات الأكثر تفاعلاً</h2>
    <a href="{{ route('web.search') }}" class="text-primary">مشاهدة الكل</a>
  </div>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
    @foreach($trending as $it)
      @include('web.partials.listing-card', ['item'=>$it])
    @endforeach
  </div>

  <div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">الأقسام</h2>
  </div>
  <div class="flex gap-3 overflow-x-auto pb-1 mb-8">
    @foreach(($categories ?? []) as $c)
      <a href="{{ route('web.search',['category'=>$c->slug]) }}"
         class="min-w-[120px] bg-white rounded-xl p-4 shadow text-center hover:shadow-md">
         <div class="text-2xl">🏷️</div>
         <div class="mt-2">{{ $c->name }}</div>
      </a>
    @endforeach
  </div>

  <div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">عقاريين قريبين منك</h2>
    <a href="{{ route('web.sellers.index') }}" class="text-primary">مشاهدة الكل</a>
  </div>
  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
    @foreach($sellers as $s)
      <a href="{{ route('web.seller.show',$s->id) }}" class="bg-white rounded-2xl p-4 shadow hover:shadow-md transition block">
        <div class="flex items-center gap-3">
          <img src="{{ $s->avatarUrl ?? '' }}" class="w-12 h-12 rounded-full object-cover" onerror="this.style.display='none'">
          <div>
            <div class="font-semibold truncate">{{ $s->name }}</div>
            <div class="text-sm text-gray700 truncate">{{ $s->accRole ?? 'وسيط عقاري' }}</div>
          </div>
        </div>
      </a>
    @endforeach
  </div>

  <div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">عقارات مميزة</h2>
  </div>
  <div class="flex gap-3 overflow-x-auto pb-1 mb-8">
    @foreach ([
      ['icon'=>'🏢','label'=>'شقق','q'=>['category'=>'apartment']],
      ['icon'=>'🏠','label'=>'فلل','q'=>['category'=>'villa']],
      ['icon'=>'🏢','label'=>'مكاتب','q'=>['category'=>'office']],
      ['icon'=>'🏕','label'=>'استراحات','q'=>['category'=>'resthouse']],
    ] as $c)
      <a href="{{ route('web.search',$c['q']) }}"
         class="min-w-[120px] bg-white rounded-xl p-4 shadow text-center hover:shadow-md">
         <div class="text-2xl">{{ $c['icon'] }}</div>
         <div class="mt-2">{{ $c['label'] }}</div>
      </a>
    @endforeach
  </div>

  <div class="flex items-center justify-between mb-3">
    <h2 class="font-bold">عقارات المملكة</h2>
  </div>
  <div class="flex gap-3 overflow-x-auto pb-1">
    @foreach ([
      ['name'=>'الرياض','img'=>'https://images.unsplash.com/photo-1602631985686-1bb0e0bc2c2b?q=80&w=1200&auto=format&fit=crop'],
      ['name'=>'جدة','img'=>'https://images.unsplash.com/photo-1602928322749-c5d0c2d0dfb3?q=80&w=1200&auto=format&fit=crop'],
      ['name'=>'الدمام','img'=>'https://images.unsplash.com/photo-1565967511849-76a60fbaed43?q=80&w=1200&auto=format&fit=crop'],
    ] as $c)
      <a href="{{ route('web.search',['city'=>$c['name']]) }}" class="relative rounded-2xl overflow-hidden w-72 h-36 shrink-0">
        <img src="{{ $c['img'] }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="absolute bottom-3 right-3 text-white font-bold">{{ $c['name'] }}</div>
      </a>
    @endforeach
  </div>
@endsection
