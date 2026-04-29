@extends('layouts.app')

@section('title', 'Khám phá Wallpaper — WallpaperHunt')
@section('meta_description', 'Tìm kiếm và khám phá hàng ngàn hình nền đẹp. Lọc theo danh mục, sắp xếp theo phổ biến nhất, mới nhất.')

@section('content')

{{-- Page Header --}}
<section class="page-header">
    <div class="page-header-container">
        <h1 class="page-header-title">
            <i class="ph-fill ph-compass text-wh-accent"></i>
            Khám phá
        </h1>
        <p class="page-header-subtitle">
            @if(request('q'))
                Kết quả tìm kiếm cho "<strong>{{ request('q') }}</strong>"
                — {{ $wallpapers->total() }} kết quả
            @else
                Khám phá wallpaper chất lượng cao từ cộng đồng
            @endif
        </p>
    </div>
</section>

{{-- Filters --}}
<section class="explore-filters" id="explore-filters">
    <div class="section-container">
        <div class="filters-bar">
            {{-- Category Filter --}}
            <div class="filter-group">
                <label class="filter-label">Danh mục</label>
                <div class="filter-pills" id="category-filters">
                    <a href="{{ route('explore', array_merge(request()->except('category', 'page'), [])) }}"
                       class="filter-pill {{ !request('category') ? 'active' : '' }}">
                        Tất cả
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('explore', array_merge(request()->except('page'), ['category' => $cat->id])) }}"
                           class="filter-pill {{ request('category') == $cat->id ? 'active' : '' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Sort Filter --}}
            <div class="filter-group">
                <label class="filter-label">Sắp xếp</label>
                <div class="filter-pills">
                    @php
                        $sorts = [
                            'newest' => ['label' => 'Mới nhất', 'icon' => 'clock'],
                            'popular' => ['label' => 'Phổ biến', 'icon' => 'fire'],
                            'most_liked' => ['label' => 'Yêu thích', 'icon' => 'heart'],
                        ];
                    @endphp
                    @foreach($sorts as $key => $sort)
                        <a href="{{ route('explore', array_merge(request()->except('page'), ['sort' => $key])) }}"
                           class="filter-pill {{ (request('sort', 'newest') == $key) ? 'active' : '' }}">
                            <i class="ph ph-{{ $sort['icon'] }}"></i>
                            {{ $sort['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Wallpaper Grid --}}
<section class="section" id="explore-grid-section">
    <div class="section-container">
        @if($wallpapers->count() > 0)
            <x-wallpaper-grid :wallpapers="$wallpapers" id="explore-grid" />

            {{-- Sentinel for Infinite Scroll --}}
            <div id="infinite-scroll-sentinel" class="flex justify-center py-8 w-full">
                @if($wallpapers->hasMorePages())
                    <div id="sentinel-loading" class="infinite-loading-spinner text-slate-500 flex items-center gap-2 text-sm font-semibold bg-white/80 backdrop-blur-md px-6 py-3 rounded-full border border-slate-200/60 shadow-sm"
                         data-next-page="{{ $wallpapers->currentPage() + 1 }}"
                         data-url="{{ route('explore') }}"
                         data-has-more="true">
                        <i class="ph ph-spinner animate-spin text-xl text-[var(--color-wh-accent)]"></i>
                        <span>Đang tải thêm wallpaper...</span>
                    </div>
                @endif
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="ph ph-image-broken"></i>
                </div>
                <h3 class="empty-state-title">Không tìm thấy wallpaper</h3>
                <p class="empty-state-text">Thử tìm kiếm với từ khóa khác hoặc thay đổi bộ lọc</p>
                <a href="{{ route('explore') }}" class="btn btn-primary">
                    <i class="ph ph-arrow-counter-clockwise"></i>
                    Xóa bộ lọc
                </a>
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sentinel = document.getElementById('sentinel-loading');
        if (!sentinel) return;

        let isLoading = false;

        const loadMore = async () => {
            if (isLoading || sentinel.dataset.hasMore === 'false') return;
            
            isLoading = true;
            const nextPage = sentinel.dataset.nextPage;
            const baseUrl = sentinel.dataset.url;
            const params = new URLSearchParams(window.location.search);
            params.set('page', nextPage);

            try {
                const res = await fetch(`${baseUrl}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();

                // Append grid data
                const grid = document.getElementById('explore-grid');
                if (grid && data.html) {
                    grid.insertAdjacentHTML('beforeend', data.html);
                }

                if (data.hasMore) {
                    sentinel.dataset.nextPage = data.nextPage;
                    isLoading = false;
                } else {
                    sentinel.dataset.hasMore = 'false';
                    sentinel.innerHTML = '<span class="text-xs text-slate-400">Đã hiển thị tất cả hình nền ✨</span>';
                    setTimeout(() => sentinel.remove(), 3000);
                }
            } catch (e) {
                isLoading = false;
                console.error('Infinite Scroll Error:', e);
            }
        };

        // Setup IntersectionObserver
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                loadMore();
            }
        }, {
            rootMargin: '300px' // Pre-load when user is 300px away
        });

        observer.observe(sentinel);
    });
</script>
@endpush
