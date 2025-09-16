<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PolicyImportController extends Controller
{
    public function importFromFiles()
    {
        // slug => file name in storage/app/policies
        $map = [
            'terms'         => 'terms_ar.md',
            'privacy'       => 'privacy_ar.md',
            'payment'       => 'payment_ar.md',
            'ip'            => 'ip_ar.md',
            'listing-rules' => 'listing-rules_ar.md',
        ];

        $rows = [];
        foreach ($map as $slug => $file) {
            $path = "policies/{$file}";
            if (!Storage::disk('local')->exists($path)) {
                continue;
            }
            $content = Storage::disk('local')->get($path);
            $rows[] = [
                'slug'      => $slug,
                'title'     => $this->titleFor($slug),
                'contentMd' => $content,
            ];
        }

        if (empty($rows)) {
            return response()->json(['ok' => false, 'error' => 'no files found in storage/app/policies'], 404);
        }

        // NOTE: your table name is `policy` (lowercase), so use that
        foreach ($rows as $r) {
            DB::table('policy')->updateOrInsert(
                ['slug' => $r['slug']],
                ['title' => $r['title'], 'contentMd' => $r['contentMd'], 'updatedAt' => now()]
            );
        }

        return response()->json(['ok' => true, 'count' => count($rows)]);
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
