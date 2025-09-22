<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title','Real Estate')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#06B580',
            gray900: '#393F42',
            gray700: '#696D70',
            gray500: '#A4ACB2',
            gray200: '#EDEDED',
            bg: '#F5F5F5',
          },
          fontFamily: { sans: ['Cairo','ui-sans-serif','system-ui'] }
        }
      }
    }
  </script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style> body{background:#F5F5F5} </style>
  @stack('head')
</head>
<body class="font-sans text-gray900">
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center gap-4">
      <a href="{{ route('web.home') }}" class="text-primary font-bold text-lg">Real Estate</a>
      <nav class="ml-auto flex items-center gap-4">
        <a class="hover:text-primary" href="{{ route('web.search') }}">بحث</a>
        <a class="hover:text-primary" href="{{ route('web.policies.index') }}">السياسات</a>
        <a class="hover:text-primary" href="{{ route('web.support') }}">خدمة العملاء</a>
        @auth
          <a class="hover:text-primary" href="{{ route('web.ads.create') }}">إضافة إعلان</a>
          <a class="hover:text-primary" href="{{ route('web.ads.mine') }}">إعلاناتي</a>
          <a class="hover:text-primary" href="{{ route('web.account') }}">حسابي</a>
          <form method="post" action="{{ route('web.logout') }}" class="inline">
            @csrf
            <button class="hover:text-primary">خروج</button>
          </form>
          <a class="hover:text-primary" href="{{ route('web.chat.index') }}">Chat</a>
        @else
          <a class="hover:text-primary" href="{{ route('login') }}">تسجيل الدخول</a>
          <a class="hover:text-primary" href="{{ route('web.register') }}">إنشاء حساب</a>
        @endauth
        <a class="hover:text-primary" href="{{ route('web.map') }}">Map</a>
      </nav>
    </div>
  </header>

  <main class="max-w-7xl mx-auto px-4 py-6">
    @if (session('success'))
      <div class="mb-4 bg-green-50 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
    @endif
    @if (session('error'))
      <div class="mb-4 bg-red-50 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
    @endif
    @yield('content')
  </main>

  <footer class="border-t bg-white">
    <div class="max-w-7xl mx-auto px-4 py-6 text-sm text-gray700">
      © {{ date('Y') }} Real Estate — جميع الحقوق محفوظة
    </div>
  </footer>
  @stack('scripts')
</body>
</html>
