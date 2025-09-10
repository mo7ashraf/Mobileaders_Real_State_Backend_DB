<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        return view('web.auth.login');
    }

    public function login(Request $request)
    {
        $data = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ])->validate();

        $user = User::where('email', $data['email'])->first();
        if (! $user || ! $user->password || ! Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['email' => 'بيانات الدخول غير صحيحة'])->withInput();
        }

        Auth::login($user, false);
        $request->session()->regenerate();
        return redirect()->intended(route('web.home'));
    }

    public function showRegister()
    {
        return view('web.auth.register');
    }

    public function register(Request $request)
    {
        $data = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:User,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:32|unique:User,phone',
        ])->validate();

        $user = new User();
        $user->id = (string) Str::uuid();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->phone = $data['phone'] ?? null;
        $user->createdAt = now();
        $user->save();

        Auth::login($user, false);
        $request->session()->regenerate();
        return redirect()->route('web.home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('web.home');
    }
}
