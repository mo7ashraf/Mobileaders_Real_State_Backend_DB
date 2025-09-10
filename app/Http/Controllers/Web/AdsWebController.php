<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdsWebController extends Controller
{
    public function create()
    {
        return view('web.ads.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'price' => 'required|integer|min:0',
            'bedrooms' => 'nullable|integer|min:0|max:20',
            'bathrooms' => 'nullable|integer|min:0|max:20',
            'areaSqm' => 'nullable|integer|min:0|max:10000',
            'status' => 'required|in:rent,sell',
            'category' => 'required|in:apartment,villa,office,resthouse',
            'imageUrl' => 'nullable|url|max:1024',
        ]);

        $listing = new Listing();
        $listing->id = (string) Str::uuid();
        $listing->sellerId = $request->user()->id;
        $listing->fill($data);
        $listing->createdAt = now();
        $listing->save();

        return redirect()->route('web.ads.mine')->with('success', 'تم إضافة الإعلان بنجاح');
    }

    public function mine(Request $request)
    {
        $listings = Listing::where('sellerId', $request->user()->id)
            ->orderBy('createdAt', 'desc')
            ->paginate(12);

        return view('web.ads.mine', compact('listings'));
    }

    public function destroy(Request $request, string $id)
    {
        $listing = Listing::where('id', $id)->where('sellerId', $request->user()->id)->firstOrFail();
        $listing->delete();
        return back()->with('success', 'تم حذف الإعلان');
    }
}

