<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PolicyImportController extends Controller
{
    /**
     * Import markdown files from storage/app/policies into `policy` table
     * and return an HTML view with the results.
     */
    public function run()
    {
        $base = storage_path('app/policies');

        $map = [
            'terms'         => 'terms_ar.md',
            'privacy'       => 'privacy_ar.md',
            'payment'       => 'payment_ar.md',
            'ip'            => 'ip_ar.md',
            'listing-rules' => 'listing-rules_ar.md',
        ];

        $titleMap = [
            'terms'         => 'الأحكام والشروط',
            'privacy'       => 'سياسة الخصوصية',
            'payment'       => 'سياسة الدفع',
            'ip'            => 'حقوق الملكية الفكرية',
            'listing-rules' => 'قواعد الإعلانات العقارية',
        ];

        // read policy table columns to decide whether updatedAt exists
        $cols = collect(DB::select('SHOW COLUMNS FROM `policy`'))->pluck('Field')->all();

        $rows = [];
        foreach ($map as $slug => $file) {
            $full = $base . DIRECTORY_SEPARATOR . $file;
            if (!is_file($full)) {
                $rows[] = ['slug'=>$slug,'file'=>$file,'missing'=>true,'bytes'=>0];
                continue;
            }

            $content = file_get_contents($full);
            if ($content !== '' && !mb_check_encoding($content, 'UTF-8')) {
                $content = mb_convert_encoding($content, 'UTF-8', 'auto');
            }

            $data = [
                'title'     => $titleMap[$slug] ?? $slug,
                'contentMd' => $content,
            ];
            if (in_array('updatedAt', $cols, true)) {
                $data['updatedAt'] = now();
            }

            DB::table('policy')->updateOrInsert(['slug'=>$slug], $data);

            $rows[] = ['slug'=>$slug,'file'=>$file,'missing'=>false,'bytes'=>strlen($content)];
        }

        return response()->view('tools.import-policies', [
            'rows' => $rows,
            'cols' => $cols,
            'base' => $base,
        ]);
    }
}
