@extends('layouts.app')
@section('title', $album->name . ' — WallpaperHunt')

@section('content')
<section class="page-header">
    <div class="page-header-container">
        <h1 class="page-header-title"><i class="ph-fill ph-folder-simple text-violet-400"></i> {{ $album->name }}</h1>
        <p class="page-header-subtitle">
            {{ $album->description ?? '' }}
            @if($album->partner)
             — bởi <a href="{{ route('artist.show', $album->partner_id) }}" class="text-wh-accent hover:underline">{{ $album->partner->full_name }}</a>
            @endif
        </p>
    </div>
</section>
<section class="section">
    <div class="section-container">
        @if($wallpapers->count() > 0)
        <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6">
            @foreach($wallpapers as $wallpaper)
                @include('partials.wallpaper-card', ['wallpaper' => $wallpaper])
            @endforeach
        </div>
        <div class="pagination-container">{{ $wallpapers->links() }}</div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="ph ph-image-broken"></i></div>
            <h3 class="empty-state-title">Album trống</h3>
        </div>
        @endif
    </div>
</section>
@endsection
