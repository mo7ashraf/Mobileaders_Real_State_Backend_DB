@extends('web.layout')
@section('title','تسجيل الدخول')

@section('content')
  <div class="max-w-md mx-auto bg-white rounded-2xl shadow p-6">
    <h1 class="font-bold text-xl mb-4">تسجيل الدخول</h1>
    @if ($errors->any())
      <div class="bg-red-50 text-red-700 p-3 rounded mb-3 text-sm">
        {{ $errors->first() }}
      </div>
    @endif
    <form method="post" action="{{ route('web.login.post') }}" class="space-y-3">
      @csrf
      <div>
        <label class="block mb-1">البريد الإلكتروني</label>
        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-xl px-3 py-2" required autocomplete="email" autocapitalize="off">
      </div>
      <div x-data="{ show: false, rtl: document.documentElement.dir === 'rtl' }" class="relative">
        <label class="block mb-1">كلمة المرور</label>
        <input :type="show ? 'text' : 'password'"
               name="password"
               class="w-full border rounded-xl h-11"
               :class="rtl ? 'pl-12 pr-3' : 'pr-12 pl-3'"
               required autocomplete="current-password">
        <button type="button"
                @click="show = !show"
                :title="show ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور'"
                role="switch"
                :aria-checked="show.toString()"
                class="absolute top-1/2 -translate-y-1/2 mt-4 z-10 grid place-items-center h-9 w-9 rounded-md hover:bg-gray200 text-gray700 hover:text-gray900 focus:outline-none focus:ring-2 focus:ring-primary/30"
                :class="rtl ? 'left-2' : 'right-2'">
          <svg x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" class="w-5 h-5">
            <g x-show="!show">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </g>
            <g x-show="show">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.5 4.5l15 15" />
            </g>
          </svg>
          <span class="sr-only">Toggle password visibility</span>
        </button>
      </div>
      <button class="w-full px-4 py-2 bg-primary text-white rounded-xl">تسجيل الدخول</button>
    </form>
    <div class="mt-4 text-center text-sm">
      ليس لديك حساب؟ <a href="{{ route('web.register') }}" class="text-primary">إنشاء حساب</a>
    </div>
  </div>
@endsection
