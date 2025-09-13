<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SellerProfile;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    private function ensureSellerProfile(User $u): void
    {
        if (!$u->sellerProfile) {
            $sp = new SellerProfile();
            $sp->id = (string) Str::uuid();
            $sp->userId = $u->id;
            $sp->verified = false;
            $sp->clients = 0;
            $sp->rating = 0;
            $sp->badges = json_encode([]);
            $sp->joinedHijri = null;
            $sp->joinedText = null;
            $sp->regionText = null;
            $sp->save();
        }
    }

    private function firstUser(): User
    {
        $u = User::orderBy('createdAt','asc')->first();
        if ($u) {
            $this->ensureSellerProfile($u);
            return $u;
        }

        $u = new User();
        $u->id        = (string) Str::uuid();
        $u->phone     = '966500000000+';
        $u->name      = '�.���������.';
        $u->createdAt = now();
        $u->save();
        $this->ensureSellerProfile($u);
        return $u;
    }

public function getProfile()
{
    // Ensure there is at least one user record
    $u = $this->firstUser();
    $sp = $u->sellerProfile; // ensured above
    return response()->json([
        'id'        => $u->id,
        'name'      => $u->name ?? '',
        'bio'       => $u->bio ?? '',
        'orgName'   => $u->orgName ?? '',
        'role'      => $u->accRole ?? 'user',
        'avatarUrl' => $u->avatarUrl ?? '',
        'phone'     => $u->phone ?? '',
        // extra fields commonly used by the app UI
        'verified'  => (bool) optional($sp)->verified,
        'rating'    => (float) (optional($sp)->rating ?? 0),
        'clients'   => (int) (optional($sp)->clients ?? 0),
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
