@extends('web.layout')
@section('title','إنشاء حساب')

@section('content')
  <div class="max-w-md mx-auto bg-white rounded-2xl shadow p-6">
    <h1 class="font-bold text-xl mb-4">إنشاء حساب</h1>
    @if ($errors->any())
      <div class="bg-red-50 text-red-700 p-3 rounded mb-3 text-sm">
        {{ $errors->first() }}
      </div>
    @endif
    <form method="post" action="{{ route('web.register.post') }}" class="space-y-3">
      @csrf
      <div>
        <label class="block mb-1">الاسم</label>
        <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded-xl px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-xl px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">رقم الجوال</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-xl px-3 py-2">
      </div>
      <div>
        <label class="block mb-1">كلمة المرور</label>
        <input type="password" name="password" class="w-full border rounded-xl px-3 py-2" required>
      </div>
      <div>
        <label class="block mb-1">تأكيد كلمة المرور</label>
        <input type="password" name="password_confirmation" class="w-full border rounded-xl px-3 py-2" required>
      </div>
      <button class="w-full px-4 py-2 bg-primary text-white rounded-xl">تسجيل</button>
    </form>
    <div class="mt-4 text-center text-sm">
      لديك حساب؟ <a href="{{ route('login') }}" class="text-primary">تسجيل الدخول</a>
    </div>
  </div>
@endsection
