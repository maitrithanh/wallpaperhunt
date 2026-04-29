@extends('layouts.app')

@section('title', 'TikTok Feed Wallpaper — WallpaperHunt')

@section('content')
<div id="tiktok-feed" class="h-screen w-full overflow-y-scroll snap-y snap-mandatory bg-black scroll-smooth relative">
        {{-- Auto Play (Slideshow) Button --}}
    <button id="autoplay-btn" onclick="toggleAutoplay()" class="fixed top-6 right-6 z-50 bg-black/40 backdrop-blur-xl border border-white/10 w-11 h-11 rounded-full flex items-center justify-center text-white hover:bg-[var(--color-wh-accent)] hover:border-[var(--color-wh-accent)] transition-all shadow-lg" title="Tự động phát">
        <i class="ph ph-play text-xl" id="autoplay-icon"></i>
    </button>

    @if($wallpapers->count() > 0)
        @include('partials.feed-items', ['wallpapers' => $wallpapers])
    @else
        <div class="h-full flex flex-col items-center justify-center text-white p-6">
            <i class="ph ph-video-camera-slash text-slate-500 text-6xl mb-4"></i>
            <h3 class="font-bold text-xl text-white">Chưa có Feed nào khả dụng</h3>
            <p class="text-slate-400 text-sm mt-1">Quay lại trang chủ để khám phá nhiều hơn.</p>
        </div>
    @endif

    @if($wallpapers->count() > 0 && $wallpapers->hasMorePages())
        <div id="feed-sentinel" class="h-20 w-full flex items-center justify-center bg-black text-slate-500" 
             data-next-page="2" 
             data-has-more="true">
            <i class="ph ph-spinner animate-spin text-2xl"></i>
        </div>
    @endif

    {{-- iPhone-style Bottom Navigation Bar --}}
    <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-slate-900/80 backdrop-blur-xl border border-white/10 rounded-full px-6 py-3 shadow-[0_20px_50px_rgba(0,0,0,0.5)] flex items-center gap-8 md:gap-12 w-[90%] max-w-md justify-around">
        <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition">
            <i class="ph ph-house text-2xl"></i>
            <span class="text-[10px] font-medium">Trang chủ</span>
        </a>
        <a href="{{ route('explore') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition">
            <i class="ph ph-compass text-2xl"></i>
            <span class="text-[10px] font-medium">Khám phá</span>
        </a>
        <a href="{{ route('feed') }}" class="flex flex-col items-center gap-1 text-[var(--color-wh-accent)]">
            <i class="ph-fill ph-film-strip text-2xl"></i>
            <span class="text-[10px] font-bold">Feed</span>
        </a>
        <a href="{{ route('upload') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition">
            <i class="ph ph-plus-circle text-2xl"></i>
            <span class="text-[10px] font-medium">Tải lên</span>
        </a>
        <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition">
            <i class="ph ph-user text-2xl"></i>
            <span class="text-[10px] font-medium">Hồ sơ</span>
        </a>
    </div>
</div>

{{-- Custom Styling --}}
<style>
    /* Hide top navbar on this page */
    .navbar {
        display: none !important;
    }
    
    /* Adjust main padding since navbar is hidden */
    main {
        padding-top: 0 !important;
    }

    /* Hide scrollbar */
    #tiktok-feed::-webkit-scrollbar {
        display: none;
    }
    #tiktok-feed {
        -ms-overflow-style: none;
        scrollbar-width: none;
        height: 100vh;
    }
</style>

@endsection

@push('scripts')
<script>
    // Auto Play (Slideshow) Mode
    let autoplayInterval = null;
    function toggleAutoplay() {
        const icon = document.getElementById('autoplay-icon');
        const btn = document.getElementById('autoplay-btn');
        const feed = document.getElementById('tiktok-feed');
        const items = document.querySelectorAll('.tiktok-item');

        if (autoplayInterval) {
            // Stop Autoplay
            clearInterval(autoplayInterval);
            autoplayInterval = null;
            icon.className = 'ph ph-play text-xl';
            btn.classList.remove('bg-blue-600');
            if (window.showToast) window.showToast('Đã dừng tự động phát.', 'info');
        } else {
            // Start Autoplay
            icon.className = 'ph-fill ph-pause text-xl';
            btn.classList.add('bg-blue-600');
            if (window.showToast) window.showToast('Bắt đầu tự động phát (8 giây/ảnh).', 'success');

            autoplayInterval = setInterval(() => {
                const scrollTop = feed.scrollTop;
                const itemHeight = window.innerHeight;
                const currentIdx = Math.round(scrollTop / itemHeight);

                if (currentIdx < items.length - 1) {
                    feed.scrollTo({ top: (currentIdx + 1) * itemHeight, behavior: 'smooth' });
                } else {
                    feed.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }, 8000);
        }
    }

    // Toggle Description
    function toggleDescription(id, btn) {
        const desc = document.getElementById(`desc-${id}`);
        if (desc.classList.contains('line-clamp-2')) {
            desc.classList.remove('line-clamp-2');
            btn.innerText = 'Rút gọn';
        } else {
            desc.classList.add('line-clamp-2');
            btn.innerText = 'Xem thêm';
        }
    }

    // AJAX Like Wallpaper
    async function likeWallpaper(id, btn) {
        try {
            const res = await fetch(`/wallpaper/${id}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (res.status === 401) {
                const errorData = await res.json();
                if (window.showToast) window.showToast(errorData.message, 'error');
                setTimeout(() => window.location.href = errorData.redirect, 1500);
                return;
            }

            const data = await res.json();
            if(data.success) {
                const countEl = btn.querySelector('.like-count');
                if(countEl) countEl.innerText = data.likes;
                
                const icon = btn.querySelector('i');
                if (data.liked) {
                    icon.className = 'ph-fill ph-heart text-red-500 text-xl group-hover:scale-110 transition duration-300';
                    if (window.showToast) window.showToast('Đã thích hình nền!', 'success');
                } else {
                    icon.className = 'ph ph-heart text-white text-xl group-hover:scale-110 transition duration-300';
                    if (window.showToast) window.showToast('Đã bỏ thích hình nền.', 'info');
                }
            }
        } catch (e) {
            if (window.showToast) {
                window.showToast('Không thể thực hiện thao tác lúc này.', 'error');
            }
        }
    }

    // Infinite Scroll using Intersection Observer
    document.addEventListener('DOMContentLoaded', () => {
        const feed = document.getElementById('tiktok-feed');
        const sentinel = document.getElementById('feed-sentinel');
        
        if (!sentinel) return;

        let loading = false;

        const observer = new IntersectionObserver(async (entries) => {
            const entry = entries[0];
            if (entry.isIntersecting && !loading && sentinel.dataset.hasMore === 'true') {
                loading = true;
                const nextPage = sentinel.dataset.nextPage;
                
                try {
                    const res = await fetch(`/feed?page=${nextPage}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await res.json();
                    
                    if (data.html.trim() !== '') {
                        sentinel.insertAdjacentHTML('beforebegin', data.html);
                        sentinel.dataset.nextPage = data.nextPage;
                    }
                    
                    if (!data.hasMore) {
                        sentinel.dataset.hasMore = 'false';
                        sentinel.remove();
                    }
                } catch (e) {
                    console.error('Error loading more feed items', e);
                } finally {
                    loading = false;
                }
            }
        }, {
            root: feed,
            rootMargin: '100px',
            threshold: 0.1
        });

        observer.observe(sentinel);
        
        // Double Tap/Click to Like
        document.addEventListener('dblclick', (e) => {
            const item = e.target.closest('.tiktok-item');
            if (!item) return;

            const id = item.dataset.id;
            const likeBtn = item.querySelector('button[onclick^="likeWallpaper"]');
            if (id && likeBtn) {
                likeWallpaper(id, likeBtn);
                
                // Show popping heart animation
                const heart = document.createElement('i');
                heart.className = 'ph-fill ph-heart text-red-500 text-6xl animate-ping';
                heart.style.position = 'fixed';
                heart.style.left = `${e.clientX}px`;
                heart.style.top = `${e.clientY}px`;
                heart.style.transform = 'translate(-50%, -50%)';
                heart.style.zIndex = '9999';
                heart.style.pointerEvents = 'none';
                document.body.appendChild(heart);
                setTimeout(() => heart.remove(), 1000);
            }
        });

        // Keyboard Navigation (Arrow keys)
        document.addEventListener('keydown', (e) => {
            const feed = document.getElementById('tiktok-feed');
            const items = document.querySelectorAll('.tiktok-item');
            if (!feed || items.length === 0) return;

            let currentIdx = 0;
            const scrollTop = feed.scrollTop;
            const itemHeight = window.innerHeight;
            currentIdx = Math.round(scrollTop / itemHeight);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (currentIdx < items.length - 1) {
                    feed.scrollTo({ top: (currentIdx + 1) * itemHeight, behavior: 'smooth' });
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (currentIdx > 0) {
                    feed.scrollTo({ top: (currentIdx - 1) * itemHeight, behavior: 'smooth' });
                }
            }
        });
    });
</script>
@endpush
