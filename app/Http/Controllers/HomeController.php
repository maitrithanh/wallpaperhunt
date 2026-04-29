<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use App\Models\Category;
use App\Models\Photos;
use App\Models\Partner;

class HomeController extends Controller
{
    public function index()
    {
        $trendingWallpapers = Photos::where('status', Photos::STATUS_PUBLIC)
            ->orderByDesc('view_count')
            ->limit(12)
            ->get();

        $latestWallpapers = Photos::where('status', Photos::STATUS_PUBLIC)
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        $categories = Category::where('status', Category::STATUS_ACTIVE)
            ->withCount(['photos' => function ($query) {
                $query->where('status', Photos::STATUS_PUBLIC);
            }])
            ->get();

        $popularAlbums = Albums::where('status', Albums::STATUS_PUBLIC)
            ->with('partner')
            ->withCount(['photos' => function ($query) {
                $query->where('status', Photos::STATUS_PUBLIC);
            }])
            ->orderByDesc('view_count')
            ->limit(6)
            ->get();

        $popularTags = \App\Models\SearchTerm::orderByDesc('search_count')
            ->limit(6)
            ->pluck('term')
            ->toArray();

        if (empty($popularTags)) {
            $popularTags = ['Thiên nhiên', 'Anime', 'Minimal', 'Abstract', 'Gaming', 'Art'];
        }

        $stats = [
            'wallpapers' => Photos::where('status', Photos::STATUS_PUBLIC)->count(),
            'artists' => \App\Models\Partner::where('status', \App\Models\Partner::STATUS_ACTIVE)->count(),
            'downloads' => Photos::where('status', Photos::STATUS_PUBLIC)->sum('view_count'),
        ];

        $likedWallpapers = [];
        if (session()->has('customer_id')) {
            $likedWallpapers = \DB::table('photo_like')
                ->where('customer_id', session('customer_id'))
                ->pluck('photo_id')
                ->toArray();
        }

        return view('home', compact(
            'popularTags',
            'trendingWallpapers',
            'latestWallpapers',
            'categories',
            'popularAlbums',
            'stats',
            'likedWallpapers'
        ));
    }

    public function searchSuggestions(\Illuminate\Http\Request $request)
    {
        $query = trim($request->get('q'));
        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        // Record search term
        if (strlen($query) >= 2) {
            $termRecord = \App\Models\SearchTerm::firstOrNew(['term' => $query]);
            if ($termRecord->exists) {
                $termRecord->increment('search_count');
            } else {
                $termRecord->search_count = 1;
                $termRecord->save();
            }
        }

        $wallpapers = Photos::where('status', Photos::STATUS_PUBLIC)
            ->where('name', 'like', '%' . $query . '%')
            ->orderByDesc('view_count')
            ->limit(5)
            ->get(['id', 'name', 'src']);

        $results = [];
        foreach ($wallpapers as $item) {
            $src = $item->src;
            if ($src && !str_starts_with($src, 'http')) {
                $src = asset('storage/' . $src);
            } elseif (!$src) {
                $src = 'https://picsum.photos/seed/' . $item->id . '/100/150';
            }

            $results[] = [
                'id' => $item->id,
                'name' => $item->name,
                'src' => $src,
                'url' => route('wallpaper.show', $item->id)
            ];
        }

        return response()->json($results);
    }
}
