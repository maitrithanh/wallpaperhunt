@props([
    'wallpapers' => [],
    'likedWallpapers' => []
])

<div {{ $attributes->merge(['class' => 'columns-1 sm:columns-2 md:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6']) }}>
    @foreach($wallpapers as $wallpaper)
        @include('partials.wallpaper-card', [
            'wallpaper' => $wallpaper, 
            'likedWallpapers' => $likedWallpapers ?? []
        ])
    @endforeach
</div>
