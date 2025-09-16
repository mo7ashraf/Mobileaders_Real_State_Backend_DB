<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PolicyImportController extends Controller
{
    public function importFromFiles()
    {
        $map = [
            'terms'          => 'terms_ar.md',
            'privacy'        => 'privacy_ar.md',
            'payment'        => 'payment_ar.md',
            'ip'             => 'ip_ar.md',
            'listing-rules'  => 'listing-rules_ar.md',
        ];

        $rows = [];
        foreach ($map as $slug => $file) {
            if (!Storage::disk('local')->exists("policies/$file")) {
                continue;
            }
            $content = Storage::disk('local')->get("policies/$file");
            $title   = $this->titleFor($slug);
            $rows[]  = ['slug'=>$slug,'title'=>$title,'contentMd'=>$content];
        }

        if (empty($rows)) return response()->json(['ok'=>false,'error'=>'no files found'],404);

        foreach ($rows as $r) {
            DB::table('Policy')->updateOrInsert(
                ['slug'=>$r['slug']],
                ['title'=>$r['title'], 'contentMd'=>$r['contentMd']]
            );
        }

        return response()->json(['ok'=>true,'count'=>count($rows)]);
    }

    private function titleFor(string $slug): string
    {
        return [
            'terms'         => 'الأحكام والشروط',
            'privacy'       => 'سياسة الخصوصية',
            'payment'       => 'سياسة الدفع',
            'ip'            => 'حقوق الملكية الفكرية',
            'listing-rules' => 'قواعد الإعلانات العقارية',
        ][$slug] ?? $slug;
    }
}
