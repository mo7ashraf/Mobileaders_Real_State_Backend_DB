@extends('web.layout')
@section('title',$p->title)
@push('head')
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
@endpush
@section('content')
  <h1 class="font-bold text-xl mb-4">{{ $p->title }}</h1>
  <div id="md" class="bg-white rounded-2xl shadow p-4 leading-8"></div>
@endsection
@push('scripts')
<script>
  const md = @json($p->contentMd);
  document.getElementById('md').innerHTML = marked.parse(md ?? '');
  // Basic RTL tweaks for markdown content
  document.querySelectorAll('#md h1,#md h2,#md h3,#md p,#md ul,#md ol').forEach(el=>{el.dir='rtl'});
  document.querySelectorAll('#md pre').forEach(el=>{el.classList.add('bg-gray-200','p-3','rounded')});
</script>
@endpush

