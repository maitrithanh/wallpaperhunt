@extends('layouts.app')

@section('title', $wallpaper->name . ' — WallpaperHunt')
@section('meta_description', $wallpaper->description ?? 'Tải hình nền ' . $wallpaper->name . ' chất lượng cao miễn phí trên WallpaperHunt.')

@section('content')
@php
    $resolution = 'N/A';
    $fileSize = 'N/A';
    $fileType = 'N/A';
    
    if ($wallpaper->src && !str_starts_with($wallpaper->src, 'http')) {
        $filePath = storage_path('app/public/' . $wallpaper->src);
        if (file_exists($filePath)) {
            $size = @getimagesize($filePath);
            if ($size) {
                $resolution = $size[0] . ' x ' . $size[1];
            }
            $bytes = @filesize($filePath);
            if ($bytes >= 1048576) {
                $fileSize = number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                $fileSize = number_format($bytes / 1024, 2) . ' KB';
            } else {
                $fileSize = $bytes . ' bytes';
            }
            $fileType = strtoupper(pathinfo($filePath, PATHINFO_EXTENSION));
        }
    } else {
        $fileType = 'IMAGE';
    }
@endphp

<div class="bg-slate-50 min-h-screen text-slate-900 antialiased font-sans relative">
    {{-- Minimalist Detail Layout --}}
    <section class="relative z-10 pt-24 pb-20 px-4 md:px-8 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            
            {{-- Left Side: Image Display (The Hero) --}}
            <div class="lg:col-span-8 flex flex-col items-center">
                <div class="relative group rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] bg-slate-950 max-h-[80vh] w-full flex justify-center items-center">
                    <img src="{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/1920/1080' }}" 
                         alt="{{ $wallpaper->name }}" 
                         class="max-h-[80vh] object-contain w-auto transition-transform duration-700 ease-out group-hover:scale-[1.02]" 
                         id="preview-image">
                         
                    {{-- Zoom overlay --}}
                    <button class="absolute bottom-6 right-6 bg-black/50 backdrop-blur-md border border-white/10 hover:bg-black/70 hover:scale-105 text-white p-3 rounded-2xl shadow-lg transition duration-300 opacity-0 group-hover:opacity-100" id="zoom-btn" title="Xem toàn màn hình">
                        <i class="ph ph-arrows-out text-xl"></i>
                    </button>
                </div>

                {{-- Comments Section (Moved to Left for Balanced Layout) --}}
                <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)] w-full mt-8">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <i class="ph-fill ph-chat-circle-dots text-[var(--color-wh-accent)] text-2xl"></i>
                        Bình luận ({{ $wallpaper->comments->count() }})
                    </h3>

                    {{-- Comment Submission Form --}}
                    <form onsubmit="submitComment(event, {{ $wallpaper->id }})" class="mb-8 flex flex-col gap-3">
                        <textarea id="comment-content" rows="3" placeholder="Chia sẻ suy nghĩ của bạn về tác phẩm này..." required
                                  class="w-full text-sm p-4 border border-slate-200/60 rounded-2xl bg-slate-50 focus:bg-white focus:ring-4 focus:ring-[var(--color-wh-accent)]/10 focus:border-[var(--color-wh-accent)] outline-none transition duration-200 resize-none shadow-inner"></textarea>
                        <button type="submit" class="common-button self-end px-6 py-2.5 text-sm flex items-center gap-2">
                            <i class="ph-fill ph-paper-plane-right text-lg"></i>
                            <span>Gửi bình luận</span>
                        </button>
                    </form>

                    {{-- Comments List --}}
                    <div class="flex flex-col gap-4 max-h-[400px] overflow-y-auto pr-2" id="comments-list">
                        @forelse($wallpaper->comments as $comment)
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-slate-800">{{ $comment->author_name }}</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <div class="text-center py-12 text-slate-400" id="no-comments-msg">
                                <i class="ph ph-chats text-4xl mb-2 block text-slate-300"></i>
                                <p class="text-sm font-medium">Chưa có bình luận nào. Hãy là người đầu tiên chia sẻ cảm xúc!</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Side: Content Panel --}}
            <div class="lg:col-span-4 sticky top-24">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 md:p-8 shadow-[0_10px_40px_rgba(0,0,0,0.04)]">
                    
                    {{-- Header --}}
                    <div class="mb-6">
                        @if($wallpaper->category)
                            <a href="{{ route('category.show', $wallpaper->category->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-semibold tracking-wide border border-slate-200 hover:bg-slate-200 transition mb-3">
                                {{ $wallpaper->category->name }}
                            </a>
                        @endif
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 tracking-tight leading-tight mb-4">{{ $wallpaper->name }}</h1>
                        
                        @if($wallpaper->partner)
                            <a href="{{ route('artist.show', $wallpaper->partner_id) }}" class="flex items-center gap-3 group mt-2">
                                <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 shadow-sm flex-shrink-0">
                                    @if($wallpaper->partner->avatar)
                                        <img src="{{ asset('storage/' . $wallpaper->partner->avatar) }}" alt="{{ $wallpaper->partner->full_name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-500">
                                            <i class="ph ph-user text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="block font-semibold text-slate-800 text-sm group-hover:text-[var(--color-wh-accent)] transition">{{ $wallpaper->partner->full_name }}</span>
                                    <span class="block text-xs text-slate-500">Nghệ sĩ phát hành</span>
                                </div>
                            </a>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if($wallpaper->description)
                        <div class="text-slate-600 text-sm leading-relaxed mb-6 pb-6 border-b border-slate-100">
                            {{ $wallpaper->description }}
                        </div>
                    @endif

                    {{-- Metadata Grid --}}
                    <div class="grid grid-cols-2 gap-4 bg-slate-50 rounded-2xl p-4 border border-slate-100/80 mb-6 text-sm">
                        <div class="flex items-center gap-2">
                            <i class="ph ph-unite text-slate-600 text-lg"></i>
                            <div>
                                <span class="block text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Độ phân giải</span>
                                <span class="font-semibold text-slate-800">{{ $resolution }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ph ph-file-zip text-slate-600 text-lg"></i>
                            <div>
                                <span class="block text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Dung lượng</span>
                                <span class="font-semibold text-slate-800">{{ $fileSize }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ph ph-image text-slate-600 text-lg"></i>
                            <div>
                                <span class="block text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Định dạng</span>
                                <span class="font-semibold text-slate-800">{{ $fileType }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="ph ph-calendar-blank text-slate-600 text-lg"></i>
                            <div>
                                <span class="block text-[10px] uppercase tracking-wider text-slate-500 font-semibold">Ngày đăng</span>
                                <span class="font-semibold text-slate-800">{{ $wallpaper->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Pricing & Stats --}}
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-slate-100">
                        @if($wallpaper->price && $wallpaper->price > 0)
                            <div class="inline-flex items-center gap-2 text-amber-600 bg-amber-50 border border-amber-200/50 font-bold px-4 py-2 rounded-xl text-base">
                                <i class="ph-fill ph-tag text-lg"></i>
                                <span>{{ number_format($wallpaper->price, 0, ',', '.') }} VNĐ</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 text-emerald-600 bg-emerald-50 border border-emerald-200/50 font-bold px-4 py-2 rounded-xl text-sm">
                                <i class="ph-fill ph-check-circle text-lg"></i>
                                <span>Tải miễn phí</span>
                            </div>
                        @endif

                        <div class="flex gap-4 text-right">
                            <div>
                                <span class="block text-base font-bold text-slate-800">{{ number_format($wallpaper->view_count) }}</span>
                                <span class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Lượt xem</span>
                            </div>
                            <div>
                                <span class="block text-base font-bold text-slate-800" id="like-count">{{ number_format($wallpaper->like_count) }}</span>
                                <span class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Yêu thích</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col gap-3 mb-4">
                        <x-button :href="route('wallpaper.download', $wallpaper->id)" variant="primary" class="py-3.5 px-6 gap-2">
                            <i class="ph ph-download-simple text-xl"></i>
                            <span>Tải về Wallpaper</span>
                        </x-button>
                        <x-button onclick="openPreviewModal()" variant="dark" class="py-3.5 px-6 gap-2">
                            <i class="ph ph-device-mobile text-xl"></i>
                            <span>Thử giao diện (Preview)</span>
                        </x-button>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <x-button id="like-btn" onclick="likeWallpaper({{ $wallpaper->id }}, this)" variant="outline" class="py-3 px-4 gap-2">
                                <i class="{{ in_array($wallpaper->id, $likedWallpapers ?? []) ? 'ph-fill ph-heart text-red-500' : 'ph ph-heart' }} text-xl"></i>
                                <span>Yêu thích</span>
                            </x-button>
                            
                            <x-button onclick="shareWallpaper()" variant="outline" class="py-3 px-4 gap-2">
                                <i class="ph ph-share-network text-xl"></i>
                                <span>Chia sẻ</span>
                            </x-button>
                        </div>
                    </div>

                    {{-- Album reference --}}
                    @if($wallpaper->album)
                        <a href="{{ route('album.show', $wallpaper->album->id) }}" class="flex items-center justify-between p-3.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-2xl text-xs text-slate-600 font-medium transition group duration-200 mt-4">
                            <span class="flex items-center gap-2">
                                <i class="ph-fill ph-folder-simple text-amber-500 text-base"></i>
                                <span>Thuộc album: <strong class="text-slate-800">{{ $wallpaper->album->name }}</strong></span>
                            </span>
                            <i class="ph ph-arrow-right text-slate-400 group-hover:translate-x-1 transition duration-200"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Related Wallpapers --}}
    @if($related->count() > 0)
    <section class="border-t border-slate-100 bg-slate-50 py-20" id="related-section">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="mb-10">
                <h2 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                    <i class="ph-fill ph-sparkle text-[var(--color-wh-accent)]"></i>
                    Có thể bạn thích
                </h2>
            </div>
            
            <div class="columns-1 sm:columns-2 md:columns-3 lg:columns-4 gap-6 space-y-6">
                @foreach($related as $wallpaper)
                    @include('partials.wallpaper-card', ['wallpaper' => $wallpaper])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Fullscreen Modal --}}
    <div class="fullscreen-modal" id="fullscreen-modal">
        <button class="fullscreen-close" id="fullscreen-close">
            <i class="ph ph-x"></i>
        </button>
        <img src="" alt="" class="fullscreen-image" id="fullscreen-image">
    </div>

    {{-- Device Preview Modal --}}
    <div id="device-preview-modal" class="fixed inset-0 z-[9999] bg-black/85 backdrop-blur-md hidden items-center justify-center p-4">
        <div class="relative max-w-4xl w-full flex flex-col items-center">
            {{-- Close Button --}}
            <button onclick="closePreviewModal()" class="absolute -top-14 right-0 md:-right-14 text-white/70 hover:text-white text-3xl transition duration-200">
                <i class="ph ph-x-circle"></i>
            </button>

            {{-- Device Frame Wrapper --}}
            <div class="flex items-center justify-center w-full" id="mockup-frame-container">
                {{-- iPhone Frame (Portrait) --}}
                <div id="iphone-mockup" class="hidden relative w-[300px] h-[600px] bg-slate-950 rounded-[45px] border-[12px] border-slate-900 shadow-[0_25px_60px_rgba(0,0,0,0.8)] overflow-hidden">
                    {{-- Notch --}}
                    <div class="absolute top-0 inset-x-0 h-6 bg-slate-900 rounded-b-2xl z-20 flex justify-center items-center">
                        <div class="w-16 h-3 bg-black rounded-full"></div>
                    </div>
                    {{-- Screen Content --}}
                    <div class="absolute inset-0 z-10">
                        <img src="{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/1920/1080' }}" 
                             alt="iPhone Wallpaper" class="w-full h-full object-cover">
                    </div>
                </div>

                {{-- MacBook Frame (Landscape) --}}
                <div id="macbook-mockup" class="hidden relative w-full max-w-[650px] flex flex-col items-center">
                    {{-- Screen --}}
                    <div class="relative w-full aspect-[16/10] bg-slate-950 rounded-t-2xl border-[12px] border-slate-900 shadow-2xl overflow-hidden">
                        <img src="{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/1920/1080' }}" 
                             alt="MacBook Wallpaper" class="w-full h-full object-cover">
                    </div>
                    {{-- Base --}}
                    <div class="relative w-[112%] h-3.5 bg-slate-700 rounded-b-md shadow-lg border-t border-slate-600"></div>
                    <div class="relative w-[25%] h-2.5 bg-slate-800 rounded-b-md shadow-md mx-auto"></div>
                </div>
            </div>

            {{-- Controls/Info --}}
            <div class="mt-6 text-center text-white">
                <span class="text-xs font-semibold px-3 py-1 bg-white/10 rounded-full text-slate-200" id="device-preview-label">Xem trước trên thiết bị</span>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Fullscreen zoom
    const zoomBtn = document.getElementById('zoom-btn');
    const modal = document.getElementById('fullscreen-modal');
    const fsImage = document.getElementById('fullscreen-image');
    const fsClose = document.getElementById('fullscreen-close');
    const previewImage = document.getElementById('preview-image');

    zoomBtn?.addEventListener('click', () => {
        fsImage.src = previewImage.src;
        fsImage.alt = previewImage.alt;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    fsClose?.addEventListener('click', () => {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    });

    modal?.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

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
            const data = await res.json();
            if(data.success) {
                const countEl = document.getElementById('like-count');
                if(countEl) countEl.innerText = data.likes.toLocaleString();
                btn.querySelector('i').className = 'ph-fill ph-heart text-pink-500';
                if (window.showToast) {
                    window.showToast('Đã thêm vào danh sách yêu thích!');
                }
            }
        } catch (e) {
            console.error(e);
        }
    }

    function shareWallpaper() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $wallpaper->name }}',
                text: 'Tải hình nền {{ $wallpaper->name }} tuyệt đẹp trên WallpaperHunt!',
                url: window.location.href,
            }).catch(console.error);
        } else {
            navigator.clipboard.writeText(window.location.href);
            if (window.showToast) {
                window.showToast('Đã sao chép liên kết vào bộ nhớ tạm!');
            } else {
                alert('Đã sao chép liên kết!');
            }
        }
    }

    function openPreviewModal() {
        const modal = document.getElementById('device-preview-modal');
        const iphone = document.getElementById('iphone-mockup');
        const macbook = document.getElementById('macbook-mockup');
        const label = document.getElementById('device-preview-label');
        const previewImg = document.getElementById('preview-image');

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        const imgSrc = previewImg ? previewImg.src : "{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/1920/1080' }}";

        const iphoneImg = iphone.querySelector('img');
        const macbookImg = macbook.querySelector('img');
        if (iphoneImg) iphoneImg.src = imgSrc;
        if (macbookImg) macbookImg.src = imgSrc;

        const img = new Image();
        img.src = imgSrc;
        
        img.onload = function() {
            if (this.naturalHeight > this.naturalWidth) {
                iphone.classList.remove('hidden');
                macbook.classList.add('hidden');
                label.innerText = 'Thử nghiệm giao diện Smartphone';
            } else {
                iphone.classList.add('hidden');
                macbook.classList.remove('hidden');
                label.innerText = 'Thử nghiệm giao diện Desktop';
            }
        }
    }

    function closePreviewModal() {
        const modal = document.getElementById('device-preview-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    async function submitComment(event, id) {
        event.preventDefault();
        const contentEl = document.getElementById('comment-content');
        const content = contentEl.value.trim();
        if(!content) return;

        try {
            const res = await fetch(`/wallpaper/${id}/comment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ content: content })
            });

            if (res.status === 401) {
                const errorData = await res.json();
                if (window.showToast) window.showToast(errorData.message, 'error');
                setTimeout(() => window.location.href = errorData.redirect, 1500);
                return;
            }

            const data = await res.json();
            if(data.success) {
                const list = document.getElementById('comments-list');
                const noMsg = document.getElementById('no-comments-msg');
                if(noMsg) noMsg.remove();

                const item = document.createElement('div');
                item.className = 'p-3 bg-slate-50/80 rounded-2xl animate-fade-in';
                item.innerHTML = `
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-slate-800">${data.comment.author}</span>
                        <span class="text-[9px] text-slate-400 font-medium">${data.comment.created_at}</span>
                    </div>
                    <p class="text-xs text-slate-600 leading-relaxed">${data.comment.content}</p>
                `;
                list.prepend(item);
                contentEl.value = '';
                
                const titleEl = document.querySelector('h3.text-slate-900');
                if (titleEl) {
                    const countMatches = titleEl.innerText.match(/\d+/);
                    if (countMatches) {
                        const newCount = parseInt(countMatches[0]) + 1;
                        titleEl.innerHTML = `<i class="ph-fill ph-chat-circle-dots text-[var(--color-wh-accent)]"></i> Bình luận (${newCount})`;
                    }
                }
                
                if (window.showToast) window.showToast('Đã đăng bình luận!', 'success');
            }
        } catch (e) {
            console.error(e);
            if (window.showToast) window.showToast('Không thể gửi bình luận.', 'error');
        }
    }
</script>
@endpush
