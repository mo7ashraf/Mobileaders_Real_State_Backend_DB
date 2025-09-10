<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileWebController extends Controller
{
    public function show(Request $request)
    {
        return view('web.account.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $u = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => ['nullable','string','max:32', \Illuminate\Validation\Rule::unique('User','phone')->ignore($u->id, 'id')],
            'avatarUrl' => 'nullable|url|max:1024',
            'bio' => 'nullable|string|max:1000',
            'orgName' => 'nullable|string|max:255',
        ]);

        $u->fill($data);
        $u->save();

        return back()->with('success', 'تم تحديث الملف بنجاح');
    }
}

