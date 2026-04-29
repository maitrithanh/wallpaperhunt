{{-- Wallpaper Grid Partial (used for AJAX loading) --}}
@foreach($wallpapers as $wallpaper)
    @include('partials.wallpaper-card', ['wallpaper' => $wallpaper])
@endforeach
