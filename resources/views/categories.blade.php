@extends('layouts.app')

@section('title', 'Danh mục — WallpaperHunt')

@section('content')
<section class="page-header">
    <div class="page-header-container">
        <h1 class="page-header-title"><i class="ph-fill ph-squares-four text-wh-accent"></i> Danh mục</h1>
        <p class="page-header-subtitle">Khám phá wallpaper theo chủ đề yêu thích</p>
    </div>
</section>
<section class="section">
    <div class="section-container">
        @if($categories->count() > 0)
        <div class="categories-page-grid">
            @foreach($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="category-page-card">
                <div class="category-page-card-bg">
                    @if($category->avatar)
                    <img src="{{ asset('storage/' . $category->avatar) }}" alt="{{ $category->name }}" onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $category->id }}/500/500';">
                    @else
                    <div class="category-page-card-placeholder" style="background: linear-gradient(135deg, hsl({{ ($loop->index * 40) % 360 }}, 70%, 40%), hsl({{ ($loop->index * 40 + 50) % 360 }}, 80%, 25%))"></div>
                    @endif
                </div>
                <div class="category-page-card-content">
                    <h3 class="category-page-card-name">{{ $category->name }}</h3>
                    <span class="category-page-card-count"><i class="ph ph-images"></i> {{ $category->photos_count ?? 0 }} ảnh</span>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon"><i class="ph ph-folder-simple-dashed"></i></div>
            <h3 class="empty-state-title">Chưa có danh mục</h3>
        </div>
        @endif
    </div>
</section>
@endsection
