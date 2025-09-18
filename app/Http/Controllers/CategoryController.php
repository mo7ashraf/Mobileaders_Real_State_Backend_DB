<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index(Request $r)
    {
        $withCounts = filter_var($r->query('withCounts', '1'), FILTER_VALIDATE_BOOLEAN);
        $onlyEnabled = filter_var($r->query('enabled', '1'), FILTER_VALIDATE_BOOLEAN);

        $q = Category::query();
        if ($onlyEnabled) $q->where('enabled', true);
        $q->orderBy('sortOrder')->orderBy('name');
        $cats = $q->get();

        $counts = [];
        if ($withCounts) {
            $counts = DB::table('listing')->select('category', DB::raw('COUNT(*) as cnt'))
                ->groupBy('category')->pluck('cnt','category');
        }

        $out = $cats->map(function($c) use ($counts, $withCounts){
            return [
                'slug'   => $c->slug,
                'name'   => $c->name,
                'icon'   => $c->icon,
                'order'  => (int)$c->sortOrder,
                'enabled'=> (bool)$c->enabled,
                'count'  => $withCounts ? (int)($counts[$c->slug] ?? 0) : null,
            ];
        });

        return response()->json($out);
    }
}
