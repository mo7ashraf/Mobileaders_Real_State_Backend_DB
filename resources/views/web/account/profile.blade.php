@extends('web.layout')
@section('title','حسابي')

@section('content')
  <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow p-6">
    <h1 class="font-bold text-xl mb-4">الملف الشخصي</h1>
    @if (session('success'))
      <div class="bg-green-50 text-green-700 p-3 rounded mb-3 text-sm">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
      <div class="bg-red-50 text-red-700 p-3 rounded mb-3 text-sm">{{ $errors->first() }}</div>
    @endif
    <form method="post" action="{{ route('web.account.update') }}" class="grid md:grid-cols-2 gap-4">
      @csrf
      <div>
        <label class="block mb-1">الاسم</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded-xl px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">رقم الجوال</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div class="md:col-span-2">
        <label class="block mb-1">رابط الصورة (Avatar URL)</label>
        <input type="url" name="avatarUrl" value="{{ old('avatarUrl', $user->avatarUrl) }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div class="md:col-span-2">
        <label class="block mb-1">الجهة</label>
        <input type="text" name="orgName" value="{{ old('orgName', $user->orgName) }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div class="md:col-span-2">
        <label class="block mb-1">نبذة</label>
        <textarea name="bio" rows="4" class="w-full border rounded-xl px-3 py-2">{{ old('bio', $user->bio) }}</textarea>
      </div>
      <div class="md:col-span-2 flex gap-3">
        <button class="px-4 py-2 bg-primary text-white rounded-xl">حفظ</button>
      </div>
    </form>
  </div>
@endsection

