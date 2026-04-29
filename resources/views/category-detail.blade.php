@extends('layouts.app')
@section('title', $category->name . ' — WallpaperHunt')

@section('content')
<section class="page-header">
    <div class="page-header-container">
        <h1 class="page-header-title"><i class="ph-fill ph-squares-four text-wh-accent"></i> {{ $category->name }}</h1>
        <p class="page-header-subtitle">{{ $category->description ?? $wallpapers->total() . ' wallpaper trong danh mục này' }}</p>
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
            <h3 class="empty-state-title">Chưa có wallpaper</h3>
        </div>
        @endif
    </div>
</section>
@endsection
