<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>استيراد السياسات</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{font-family:system-ui,Segoe UI,Roboto,sans-serif;margin:2rem}
    table{width:100%;border-collapse:collapse;margin-top:1rem}
    td,th{border:1px solid #ddd;padding:.5rem}
    th{background:#f5f5f5}
    .ok{color:#0a7b20;font-weight:600}
    .err{color:#c00;font-weight:600}
    code{background:#f6f8fa;padding:.2rem .4rem;border-radius:.25rem}
  </style>
</head>
<body>
  <h1>استيراد السياسات من الملفات → قاعدة البيانات</h1>
  <p>المجلد: <code>{{ $base }}</code></p>
  <table>
    <thead>
      <tr><th>Slug</th><th>الملف</th><th>النتيجة</th><th>الحجم (بايت)</th></tr>
    </thead>
    <tbody>
      @foreach($rows as $r)
        <tr>
          <td>{{ $r['slug'] }}</td>
          <td>{{ $r['file'] }}</td>
          @if(!empty($r['missing']))
            <td class="err">ملف غير موجود</td>
            <td>0</td>
          @else
            <td class="ok">تم التحديث / الإدراج</td>
            <td>{{ $r['bytes'] }}</td>
          @endif
        </tr>
      @endforeach
    </tbody>
  </table>
  <p>أعمدة جدول <code>policy</code>: {{ implode(', ', $cols) }}</p>
</body>
</html>
