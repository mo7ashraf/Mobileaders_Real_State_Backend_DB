@extends('web.layout')
@section('title','خدمة العملاء')
@section('content')
  <div class="bg-white rounded-2xl shadow p-4">
    <h1 class="font-bold text-lg mb-4">خدمة العملاء</h1>
    <div class="space-y-2">
      <div>واتساب: <a class="text-primary" href="https://wa.me/{{ $support->whatsapp ?? '' }}">{{ $support->whatsapp ?? '—' }}</a></div>
      <div>البريد:  <a class="text-primary" href="mailto:{{ $support->email ?? '' }}">{{ $support->email ?? '—' }}</a></div>
    </div>
  </div>
@endsection

