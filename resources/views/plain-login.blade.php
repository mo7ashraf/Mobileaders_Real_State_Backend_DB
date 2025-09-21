<!doctype html><html lang="en"><head><meta charset="utf-8"><title>Admin Login</title></head>
<body style="font-family:sans-serif;max-width:360px;margin:40px auto">
  <h2>Admin Login (Fallback)</h2>
  @if ($errors->any()) <div style="color:red">{{ $errors->first() }}</div> @endif
  <form method="POST" action="/admin/plain-login">
    @csrf
    <div><label>Email</label><input name="email" type="email" required style="width:100%"></div>
    <div style="margin-top:8px"><label>Password</label><input name="password" type="password" required style="width:100%"></div>
    <button style="margin-top:12px">Sign in</button>
  </form>
</body></html>
