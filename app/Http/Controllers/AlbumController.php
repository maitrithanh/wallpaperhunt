<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use App\Models\Photos;

class AlbumController extends Controller
{
    public function show($id)
    {
        $album = Albums::where('status', Albums::STATUS_PUBLIC)
            ->with('partner')
            ->withCount(['photos' => function ($query) {
                $query->where('status', Photos::STATUS_PUBLIC);
            }])
            ->findOrFail($id);

        $wallpapers = Photos::where('status', Photos::STATUS_PUBLIC)
            ->where('album_id', $album->id)
            ->with('partner')
            ->orderByDesc('created_at')
            ->paginate(24);

        return view('album-detail', compact('album', 'wallpapers'));
    }
}
