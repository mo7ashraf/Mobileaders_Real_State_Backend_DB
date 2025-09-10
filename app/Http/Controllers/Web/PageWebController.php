<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\SupportSetting;

class PageWebController extends Controller
{
    public function policiesIndex()
    {
        $policies = Policy::orderBy('slug')->get();
        return view('web.policies-index', compact('policies'));
    }

    public function policy(string $slug)
    {
        $p = Policy::where('slug', $slug)->firstOrFail();
        return view('web.policy', compact('p'));
    }

    public function support()
    {
        $s = SupportSetting::find(1);
        return view('web.support', ['support' => $s]);
    }
}

