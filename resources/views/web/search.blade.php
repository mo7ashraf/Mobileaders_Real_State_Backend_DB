@extends('web.layout')
@section('title','بحث')

@section('content')
  <div class="bg-white rounded-2xl shadow p-4 mb-4">
    <div class="flex items-center gap-3 overflow-x-auto">
      <a href="{{ route('web.search', array_filter(['status'=>$filters['status'] ?? null])) }}" class="px-3 py-1 rounded-full border {{ empty($filters['category']) ? 'bg-primary text-white border-primary' : '' }}">All</a>
      @foreach(($categories ?? []) as $c)
        <a href="{{ route('web.search', array_filter(['category'=>$c->slug,'status'=>$filters['status'] ?? null])) }}"
           class="px-3 py-1 rounded-full border {{ ($filters['category'] ?? null)===$c->slug ? 'bg-primary text-white border-primary' : '' }}">{{ $c->name }}</a>
      @endforeach
      <div class="ml-auto flex items-center gap-2">
        <span class="text-sm text-gray700">Status:</span>
        <a href="{{ route('web.search', array_filter(['category'=>$filters['category'] ?? null])) }}" class="px-2 py-1 rounded-full border {{ empty($filters['status']) ? 'bg-primary text-white border-primary' : '' }}">All</a>
        <a href="{{ route('web.search', array_filter(['category'=>$filters['category'] ?? null,'status'=>'rent'])) }}" class="px-2 py-1 rounded-full border {{ ($filters['status'] ?? null)==='rent' ? 'bg-primary text-white border-primary' : '' }}">Rent</a>
        <a href="{{ route('web.search', array_filter(['category'=>$filters['category'] ?? null,'status'=>'sell'])) }}" class="px-2 py-1 rounded-full border {{ ($filters['status'] ?? null)==='sell' ? 'bg-primary text-white border-primary' : '' }}">Sell</a>
      </div>
    </div>
  </div>
  <form method="get" class="bg-white rounded-2xl shadow p-4 mb-4 grid md:grid-cols-6 gap-3">
    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="ابحث بالعنوان أو العنوان التفصيلي"
           class="md:col-span-2 border rounded-xl px-3 py-2">
    <input type="text" name="city" value="{{ $filters['city'] ?? '' }}" placeholder="المدينة"
           class="border rounded-xl px-3 py-2">
    <select name="category" class="border rounded-xl px-3 py-2">
      <option value="">النوع</option>
      @foreach (['apartment'=>'شقة','villa'=>'فيلا','office'=>'مكتب','resthouse'=>'استراحة'] as $k=>$v)
        <option value="{{ $k }}" @selected(($filters['category'] ?? '')===$k)>{{ $v }}</option>
      @endforeach
    </select>
    <select name="status" class="border rounded-xl px-3 py-2">
      <option value="">الحالة</option>
      <option value="rent" @selected(($filters['status'] ?? '')==='rent')>للإيجار</option>
      <option value="sell" @selected(($filters['status'] ?? '')==='sell')>للبيع</option>
    </select>
    <div class="flex gap-2">
      <input type="number" name="minPrice" value="{{ $filters['minPrice'] ?? '' }}" placeholder="حد أدنى"
             class="border rounded-xl px-3 py-2 w-full">
      <input type="number" name="maxPrice" value="{{ $filters['maxPrice'] ?? '' }}" placeholder="حد أقصى"
             class="border rounded-xl px-3 py-2 w-full">
    </div>
    <div class="md:col-span-6 flex gap-3">
      <button class="px-4 py-2 bg-primary text-white rounded-xl">بحث</button>
      <a href="{{ route('web.search') }}" class="px-4 py-2 rounded-xl border">إعادة تعيين</a>
    </div>
  </form>

  <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($listings as $it)
      @include('web.partials.listing-card',['item'=>$it])
    @endforeach
  </div>
  <div class="mt-6">
    {{ $listings->links() }}
  </div>
@endsection
