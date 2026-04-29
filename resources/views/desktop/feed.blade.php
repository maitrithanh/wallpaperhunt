@extends('layouts.app')

@section('title', 'TikTok Feed Wallpaper — WallpaperHunt')

@section('content')
<div id="tiktok-feed" class="h-screen w-full overflow-y-scroll snap-y snap-mandatory bg-slate-50 scroll-smooth relative">
    {{-- Auto Play (Slideshow) Button --}}
    <button id="autoplay-btn" onclick="toggleAutoplay()" class="fixed top-6 right-6 z-50 bg-white/80 backdrop-blur-xl border border-slate-200 w-11 h-11 rounded-full flex items-center justify-center text-slate-700 hover:bg-[var(--color-wh-accent)] hover:text-white hover:border-[var(--color-wh-accent)] transition-all shadow-lg" title="Tự động phát">
        <i class="ph ph-play text-xl" id="autoplay-icon"></i>
    </button>

    @if($wallpapers->count() > 0)
        @include('partials.feed-items', ['wallpapers' => $wallpapers])
    @else
        <div class="h-full flex flex-col items-center justify-center text-slate-800 p-6">
            <i class="ph ph-video-camera-slash text-slate-400 text-6xl mb-4"></i>
            <h3 class="font-bold text-xl text-slate-900">Chưa có Feed nào khả dụng</h3>
            <p class="text-slate-500 text-sm mt-1">Quay lại trang chủ để khám phá nhiều hơn.</p>
        </div>
    @endif

    @if($wallpapers->count() > 0 && $wallpapers->hasMorePages())
        <div id="feed-sentinel" class="h-20 w-full flex items-center justify-center bg-slate-50 text-slate-400" 
             data-next-page="2" 
             data-has-more="true">
            <i class="ph ph-spinner animate-spin text-2xl"></i>
        </div>
    @endif
</div>

<style>
    .navbar { display: none !important; }
    main { padding-top: 0 !important; }
    #tiktok-feed::-webkit-scrollbar { display: none; }
    #tiktok-feed { -ms-overflow-style: none; scrollbar-width: none; height: 100vh; }
    #mobile-bottom-nav { display: flex !important; }
</style>
@endsection

@push('scripts')
<script>
    let autoplayInterval = null;
    function toggleAutoplay() {
        const icon = document.getElementById('autoplay-icon');
        const btn = document.getElementById('autoplay-btn');
        const feed = document.getElementById('tiktok-feed');
        const items = document.querySelectorAll('.tiktok-item');

        if (autoplayInterval) {
            clearInterval(autoplayInterval);
            autoplayInterval = null;
            icon.className = 'ph ph-play text-xl';
            btn.classList.remove('bg-blue-600');
            if (window.showToast) window.showToast('Đã dừng tự động phát.', 'info');
        } else {
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
                    icon.className = 'ph ph-heart text-slate-700 text-xl group-hover:scale-110 transition duration-300';
                    if (window.showToast) window.showToast('Đã bỏ thích hình nền.', 'info');
                }
            }
        } catch (e) {
            if (window.showToast) window.showToast('Không thể thực hiện thao tác lúc này.', 'error');
        }
    }

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
                    console.error('Error loading more feed', e);
                } finally {
                    loading = false;
                }
            }
        }, { root: feed, rootMargin: '100px', threshold: 0.1 });

        observer.observe(sentinel);
        
        document.addEventListener('dblclick', (e) => {
            const item = e.target.closest('.tiktok-item');
            if (!item) return;

            const id = item.dataset.id;
            const likeBtn = item.querySelector('button[onclick^="likeWallpaper"]');
            if (id && likeBtn) {
                likeWallpaper(id, likeBtn);
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

        document.addEventListener('keydown', (e) => {
            const feed = document.getElementById('tiktok-feed');
            const items = document.querySelectorAll('.tiktok-item');
            if (!feed || items.length === 0) return;

            let currentIdx = Math.round(feed.scrollTop / window.innerHeight);

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (currentIdx < items.length - 1) {
                    feed.scrollTo({ top: (currentIdx + 1) * window.innerHeight, behavior: 'smooth' });
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (currentIdx > 0) {
                    feed.scrollTo({ top: (currentIdx - 1) * window.innerHeight, behavior: 'smooth' });
                }
            }
        });
    });
</script>
@endpush
