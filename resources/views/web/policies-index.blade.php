@extends('web.layout')
@section('title','السياسات والأحكام')
@section('content')
  <h1 class="font-bold text-xl mb-4">السياسات والأحكام</h1>
  <div class="bg-white rounded-2xl shadow divide-y">
    @foreach($policies as $p)
      <a class="block px-4 py-3 hover:bg-gray-50" href="{{ route('web.policies.show',$p->slug) }}">
        <div class="font-semibold">{{ $p->title }}</div>
        <div class="text-sm text-gray700">/{{ $p->slug }}</div>
      </a>
    @endforeach
  </div>
@endsection

