@extends('web.layout')
@section('title','إضافة إعلان')

@section('content')
  <div class="max-w-3xl mx-auto bg-white rounded-2xl shadow p-6">
    <h1 class="font-bold text-xl mb-4">إضافة إعلان</h1>
    @if ($errors->any())
      <div class="bg-red-50 text-red-700 p-3 rounded mb-3 text-sm">{{ $errors->first() }}</div>
    @endif
    <form method="post" action="{{ route('web.ads.store') }}" class="grid md:grid-cols-2 gap-4">
      @csrf
      <div>
        <label class="block mb-1">العنوان</label>
        <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded-xl px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">Latitude</label>
        <input type="number" name="latitude" step="any" value="{{ old('latitude') }}" class="w-full border rounded-xl px-3 py-2" placeholder="e.g. 24.7136">
      </div>
      <div>
        <label class="block mb-1">Longitude</label>
        <input type="number" name="longitude" step="any" value="{{ old('longitude') }}" class="w-full border rounded-xl px-3 py-2" placeholder="e.g. 46.6753">
      </div>
      <div>
        <label class="block mb-1">المدينة</label>
        <input type="text" name="city" value="{{ old('city') }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div class="md:col-span-2">
        <label class="block mb-1">العنوان التفصيلي</label>
        <input type="text" name="address" value="{{ old('address') }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div>
        <label class="block mb-1">السعر (ر.س)</label>
        <input type="number" name="price" value="{{ old('price') }}" class="w-full border rounded-xl px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">المساحة (م²)</label>
        <input type="number" name="areaSqm" value="{{ old('areaSqm') }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div>
        <label class="block mb-1">غرف النوم</label>
        <input type="number" name="bedrooms" value="{{ old('bedrooms') }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div>
        <label class="block mb-1">الحمامات</label>
        <input type="number" name="bathrooms" value="{{ old('bathrooms') }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div>
        <label class="block mb-1">الحالة</label>
        <select name="status" class="w-full border rounded-xl px-3 py-2" required>
          <option value="rent" @selected(old('status')==='rent')>للإيجار</option>
          <option value="sell" @selected(old('status')==='sell')>للبيع</option>
        </select>
      </div>
      <div>
        <label class="block mb-1">النوع</label>
        <select name="category" class="w-full border rounded-xl px-3 py-2" required>
          @foreach (['apartment'=>'شقة','villa'=>'فيلا','office'=>'مكتب','resthouse'=>'استراحة'] as $k=>$v)
            <option value="{{ $k }}" @selected(old('category')===$k)>{{ $v }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-2">
        <label class="block mb-1">رابط الصورة</label>
        <input type="url" name="imageUrl" value="{{ old('imageUrl') }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div class="md:col-span-2 flex gap-3">
        <button class="px-4 py-2 bg-primary text-white rounded-xl">نشر الإعلان</button>
        <a href="{{ route('web.ads.mine') }}" class="px-4 py-2 rounded-xl border">إعلاناتي</a>
      </div>
    </form>
  </div>
@endsection
