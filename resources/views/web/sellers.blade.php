@extends('web.layout')
@section('title','العقاريين')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="font-bold text-xl">العقاريين</h1>
    <form method="get" class="flex items-center gap-2">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="ابحث بالاسم"
             class="border rounded-xl px-3 py-2">
      <button class="px-3 py-2 rounded-xl border">بحث</button>
    </form>
  </div>

  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
    @forelse($sellers as $s)
      @php $profile = $profiles[$s->id] ?? null; @endphp
      <a href="{{ route('web.seller.show',$s->id) }}" class="bg-white rounded-2xl p-4 shadow hover:shadow-md transition block">
        <div class="flex items-center gap-3">
          <img src="{{ $s->avatarUrl ?? '' }}" class="w-12 h-12 rounded-full object-cover" onerror="this.style.display='none'">
          <div>
            <div class="font-semibold truncate">{{ $s->name }}</div>
            <div class="text-sm text-gray700 truncate">{{ $s->accRole ?? 'وسيط عقاري' }}</div>
          </div>
        </div>
        <div class="mt-3 text-sm text-gray700 flex gap-4">
          <span>عميل: <b>{{ (int)optional($profile)->clients }}</b></span>
          <span>تقييم: <b>{{ (float)optional($profile)->rating }}</b></span>
        </div>
      </a>
    @empty
      <div class="col-span-full bg-white rounded-2xl p-6 text-center">لا يوجد نتائج.</div>
    @endforelse
  </div>
  {{ $sellers->links() }}
@endsection

