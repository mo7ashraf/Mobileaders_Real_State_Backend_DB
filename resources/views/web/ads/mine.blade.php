@extends('web.layout')
@section('title','إعلاناتي')

@section('content')
  <div class="flex items-center justify-between mb-4">
    <h1 class="font-bold text-xl">إعلاناتي</h1>
    <a href="{{ route('web.ads.create') }}" class="px-4 py-2 bg-primary text-white rounded-xl">إضافة إعلان</a>
  </div>

  @if (session('success'))
    <div class="bg-green-50 text-green-700 p-3 rounded mb-3 text-sm">{{ session('success') }}</div>
  @endif

  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($listings as $it)
      <div class="relative">
        @include('web.partials.listing-card',['item'=>$it])
        <form method="post" action="{{ route('web.ads.delete',$it->id) }}" class="absolute top-2 left-2">
          @csrf
          <button class="text-xs bg-red-600 text-white px-2 py-1 rounded" onclick="return confirm('حذف الإعلان؟')">حذف</button>
        </form>
      </div>
    @empty
      <div class="col-span-full bg-white rounded-2xl p-6 text-center">لا توجد إعلانات بعد.</div>
    @endforelse
  </div>
  <div class="mt-6">{{ $listings->links() }}</div>
@endsection

