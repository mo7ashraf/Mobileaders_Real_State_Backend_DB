<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function requestCode(Request $request)
    {
        $data = Validator::make($request->all(), [
            'phone' => 'required|string|max:32',
        ])->validate();

        $code = (string) random_int(100000, 999999);
        DB::table('userotp')->insert([
            'phone' => $data['phone'],
            'code' => $code,
            'expiresAt' => now()->addMinutes(5),
            'createdAt' => now(),
        ]);

        if (app()->environment('local')) {
            Log::info('OTP code for ' . $data['phone'] . ': ' . $code);
            return response()->json(['sent' => true, 'debugCode' => $code]);
        }

        // TODO: integrate SMS provider here.
        return response()->json(['sent' => true]);
    }

    public function verifyCode(Request $request)
    {
        $data = Validator::make($request->all(), [
            'phone' => 'required|string|max:32',
            'code' => 'required|string|max:10',
        ])->validate();

        $row = DB::table('userotp')
            ->where('phone', $data['phone'])
            ->where('code', $data['code'])
            ->whereNull('consumedAt')
            ->where('expiresAt', '>=', now())
            ->orderByDesc('id')
            ->first();

        if (! $row) {
            return response()->json(['message' => 'Invalid or expired code'], 422);
        }

        DB::table('userotp')->where('id', $row->id)->update(['consumedAt' => now()]);

        $user = User::where('phone', $data['phone'])->first();
        if (! $user) {
            $user = new User();
            $user->id = (string) Str::uuid();
            $user->phone = $data['phone'];
            $user->name = $data['phone'];
            $user->createdAt = now();
            $user->save();
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'avatarUrl' => $user->avatarUrl,
            ],
        ]);
    }
}
