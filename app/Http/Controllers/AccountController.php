<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    private function firstUser(): User
    {
        $u = User::orderBy('createdAt','asc')->first();
        if ($u) return $u;

        $u = new User();
        $u->id        = (string) Str::uuid();
        $u->phone     = '966500000000+';
        $u->name      = 'مستخدم';
        $u->createdAt = now();
        $u->save();
        return $u;
    }

public function getProfile()
{
    $u = User::orderBy('createdAt','asc')->first();
    return response()->json([
        'id'        => $u->id ?? null,        // ✅ أضف هذا
        'name'      => $u->name ?? '',
        'bio'       => $u->bio ?? '',
        'orgName'   => $u->orgName ?? '',
        'role'      => $u->accRole ?? 'وسيط عقاري',
        'avatarUrl' => $u->avatarUrl ?? '',
        'phone'     => $u->phone ?? '',
    ]);
}

    public function setProfile(Request $r)
    {
        $u = $this->firstUser();
        $u->name      = $r->input('name', $u->name);
        $u->bio       = $r->input('bio', $u->bio);
        $u->orgName   = $r->input('orgName', $u->orgName);
        $u->accRole   = $r->input('role', $u->accRole);
        $u->avatarUrl = $r->input('avatarUrl', $u->avatarUrl);
        $u->save();
        return response()->json(['ok'=>true]);
    }

    public function setAvatar(Request $r)
    {
        $u = $this->firstUser();
        $u->avatarUrl = $r->input('avatarUrl', '');
        $u->save();
        return response()->json(['ok'=>true,'avatarUrl'=>$u->avatarUrl]);
    }

    public function getChannels()
    {
        $u  = User::orderBy('createdAt','asc')->first();
        $ch = $u && $u->channels ? json_decode($u->channels, true) : ['chatInApp'=>true,'whatsapp'=>false,'call'=>false];
        return response()->json($ch);
    }

    public function setChannels(Request $r)
    {
        $u = $this->firstUser();
        $u->channels = json_encode($r->all(), JSON_UNESCAPED_UNICODE);
        $u->save();
        return response()->json(['ok'=>true,'channels'=>json_decode($u->channels,true)]);
    }

    public function getLinks()
    {
        $u = User::orderBy('createdAt','asc')->first();
        $links = $u && $u->socialLinks ? json_decode($u->socialLinks,true)
                : ['twitter'=>'','snapchat'=>'','tiktok'=>'','facebook'=>'','website'=>''];
        return response()->json($links);
    }

    public function setLinks(Request $r)
    {
        $u = $this->firstUser();
        $u->socialLinks = json_encode($r->all(), JSON_UNESCAPED_UNICODE);
        $u->save();
        return response()->json(['ok'=>true,'links'=>json_decode($u->socialLinks,true)]);
    }
}
