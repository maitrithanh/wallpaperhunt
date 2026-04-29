{{-- Wallpaper Card Partial --}}
@php
    $isOwnerCard = false;
    if(session()->has('customer_id')) {
        $cardCustomer = \App\Models\Customer::find(session('customer_id'));
        if($cardCustomer && $wallpaper->partner && $cardCustomer->email === $wallpaper->partner->email) {
            $isOwnerCard = true;
        }
    }
@endphp
<div class="wallpaper-card group relative break-inside-avoid mb-6" data-wallpaper-id="{{ $wallpaper->id }}">
    <a href="{{ route('wallpaper.show', $wallpaper->id) }}" class="wallpaper-card-image-link relative block">
        @if($isOwnerCard)
            <div class="absolute top-3 left-3 z-30">
                @if($wallpaper->status == \App\Models\Photos::STATUS_PENDING)
                    <span class="px-2.5 py-1 bg-amber-500 text-white text-[10px] font-bold rounded-full shadow-md flex items-center gap-1 backdrop-blur-sm bg-opacity-90">
                        <i class="ph-fill ph-clock"></i> Đang duyệt
                    </span>
                @elseif($wallpaper->status == \App\Models\Photos::STATUS_PRIVATE)
                    <span class="px-2.5 py-1 bg-slate-800 text-white text-[10px] font-bold rounded-full shadow-md flex items-center gap-1 backdrop-blur-sm bg-opacity-90">
                        <i class="ph-fill ph-eye-slash"></i> Đang ẩn
                    </span>
                @elseif($wallpaper->status == \App\Models\Photos::STATUS_PUBLIC)
                    <span class="px-2.5 py-1 bg-green-600 text-white text-[10px] font-bold rounded-full shadow-md flex items-center gap-1 backdrop-blur-sm bg-opacity-90">
                        <i class="ph-fill ph-check-circle"></i> Công khai
                    </span>
                @elseif($wallpaper->status == \App\Models\Photos::STATUS_DEACTIVATED)
                    <span class="px-2.5 py-1 bg-red-600 text-white text-[10px] font-bold rounded-full shadow-md flex items-center gap-1 backdrop-blur-sm bg-opacity-90">
                        <i class="ph-fill ph-prohibit"></i> Bị từ chối
                    </span>
                @endif
            </div>
        @endif

        <img
            src="{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/800/1200' }}"
            alt="{{ $wallpaper->name }}"
            class="wallpaper-card-image"
            loading="lazy"
            onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $wallpaper->id }}/800/1200';"
        >
        <div class="wallpaper-card-overlay">
            <div class="wallpaper-card-actions">
                <button
                    class="wallpaper-action-btn like-btn"
                    onclick="event.preventDefault(); event.stopPropagation(); likeWallpaper({{ $wallpaper->id }}, this)"
                    title="Yêu thích"
                >
                    <i class="{{ in_array($wallpaper->id, $likedWallpapers ?? []) ? 'ph-fill ph-heart text-red-500' : 'ph ph-heart' }}"></i>
                </button>
                <a
                    href="{{ route('wallpaper.download', $wallpaper->id) }}"
                    class="wallpaper-action-btn download-btn"
                    onclick="event.stopPropagation()"
                    title="Tải xuống"
                >
                    <i class="ph ph-download-simple"></i>
                </a>

                @if($isOwnerCard && in_array($wallpaper->status, [\App\Models\Photos::STATUS_PUBLIC, \App\Models\Photos::STATUS_PRIVATE]))
                    <form action="{{ route('wallpaper.toggle-status', $wallpaper->id) }}" method="POST" class="inline" onclick="event.stopPropagation()">
                        @csrf
                        <button
                            type="submit"
                            class="wallpaper-action-btn"
                            title="{{ $wallpaper->status == \App\Models\Photos::STATUS_PRIVATE ? 'Công khai ảnh' : 'Ẩn hình ảnh' }}"
                        >
                            <i class="ph-bold {{ $wallpaper->status == \App\Models\Photos::STATUS_PRIVATE ? 'ph-eye' : 'ph-eye-slash' }}"></i>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </a>
    <div class="wallpaper-card-info">
        <a href="{{ route('wallpaper.show', $wallpaper->id) }}" class="wallpaper-card-title">{{ $wallpaper->name }}</a>
        <div class="wallpaper-card-meta">
            @if($wallpaper->partner)
                <a href="{{ route('artist.show', $wallpaper->partner_id) }}" class="wallpaper-card-artist">
                    <div class="wallpaper-card-avatar">
                        @if($wallpaper->partner->avatar)
                            <img src="{{ str_starts_with($wallpaper->partner->avatar, 'http') ? $wallpaper->partner->avatar : asset('storage/' . $wallpaper->partner->avatar) }}" alt="{{ $wallpaper->partner->full_name }}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($wallpaper->partner->full_name) }}&background=random';">
                        @else
                            <i class="ph-fill ph-user"></i>
                        @endif
                    </div>
                    <span>{{ $wallpaper->partner->full_name }}</span>
                </a>
            @endif
            <div class="wallpaper-card-stats">
                <span><i class="ph ph-eye"></i> {{ number_format($wallpaper->view_count) }}</span>
                <span><i class="ph ph-heart"></i> {{ number_format($wallpaper->like_count) }}</span>
            </div>
        </div>
    </div>
</div>
