@foreach($wallpapers as $wallpaper)
<div class="tiktok-item snap-start relative w-full h-screen bg-slate-50 flex items-center justify-center overflow-hidden" data-id="{{ $wallpaper->id }}">
    
    {{-- Immersive Background (Blurred & Lightened) --}}
    <div class="absolute inset-0 opacity-15 blur-2xl scale-105 pointer-events-none">
        <img src="{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/1920/1080' }}" alt="" class="w-full h-full object-cover">
    </div>
    
    {{-- Light Gradient Overlays (Top and Bottom) --}}
    <div class="absolute inset-x-0 top-0 h-1/4 bg-gradient-to-b from-white/80 to-transparent pointer-events-none z-10"></div>
    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-slate-100 via-slate-50/80 to-transparent pointer-events-none z-10"></div>

    {{-- Main Image (The Hero) --}}
    <div class="relative z-20 max-h-screen max-w-full flex items-center justify-center p-4 md:p-8 pb-24 md:pb-32">
        {{-- Ambient Glow --}}
        <div class="absolute z-10 w-full h-full max-h-[68vh] md:max-h-[78vh] rounded-2xl opacity-40 blur-3xl scale-105 pointer-events-none animate-pulse">
            <img src="{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/1920/1080' }}" alt="" class="w-full h-full object-cover rounded-2xl">
        </div>

        {{-- Actual Image --}}
        <img src="{{ $wallpaper->src ? (str_starts_with($wallpaper->src, 'http') ? $wallpaper->src : asset('storage/' . $wallpaper->src)) : 'https://picsum.photos/seed/' . $wallpaper->id . '/1920/1080' }}" 
             alt="{{ $wallpaper->name }}" 
             class="relative z-20 max-h-[68vh] md:max-h-[78vh] max-w-full rounded-2xl object-contain shadow-[0_20px_60px_rgba(15,23,42,0.15)] border border-slate-200 transition-all duration-500 ease-out">
    </div>

    {{-- Floating Actions (Right Side) --}}
    <div class="absolute right-3 md:right-6 bottom-32 z-30 flex flex-col items-center gap-5">
        {{-- Artist Profile --}}
        @if($wallpaper->partner)
        <a href="{{ route('artist.show', $wallpaper->partner->id) }}" class="flex flex-col items-center group relative">
            <div class="w-11 h-11 rounded-full p-[2px] bg-gradient-to-tr from-[var(--color-wh-accent)] to-[var(--color-wh-accent-2)] shadow-md transform transition duration-300 group-hover:scale-110">
                <div class="w-full h-full rounded-full border-2 border-white overflow-hidden">
                    <img src="{{ $wallpaper->partner->avatar ? asset('storage/' . $wallpaper->partner->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($wallpaper->partner->full_name) . '&background=random' }}" 
                         alt="{{ $wallpaper->partner->full_name }}" class="w-full h-full object-cover">
                </div>
            </div>
            <div class="absolute -bottom-1 bg-gradient-to-r from-[var(--color-wh-accent)] to-[var(--color-wh-accent-2)] text-white text-[7px] font-bold px-1 py-0.5 rounded-full shadow scale-90">
                PRO
            </div>
            <span class="text-[9px] text-slate-800 font-medium mt-1.5 opacity-0 group-hover:opacity-100 transition duration-200 absolute -top-6 bg-white shadow-md px-2 py-0.5 rounded-md whitespace-nowrap border border-slate-100">{{ $wallpaper->partner->full_name }}</span>
        </a>
        @endif

        {{-- Like Button --}}
        <button onclick="likeWallpaper({{ $wallpaper->id }}, this)" class="flex flex-col items-center group">
            <div class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-700 shadow-md flex items-center justify-center group-hover:bg-red-50 group-hover:border-red-200 transform transition-all duration-300">
                <i class="{{ in_array($wallpaper->id, $likedWallpapers ?? []) ? 'ph-fill ph-heart text-red-500' : 'ph ph-heart' }} text-xl group-hover:scale-110 transition duration-300"></i>
            </div>
            <span class="text-[10px] text-slate-800 font-bold mt-1 like-count">{{ $wallpaper->like_count }}</span>
        </button>

        {{-- Download Button --}}
        <a href="{{ route('wallpaper.download', $wallpaper->id) }}" class="flex flex-col items-center group">
            <div class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-700 shadow-md flex items-center justify-center group-hover:bg-[var(--color-wh-accent)]/10 group-hover:text-[var(--color-wh-accent)] group-hover:border-[var(--color-wh-accent)]/20 transform transition-all duration-300">
                <i class="ph-fill ph-download-simple text-xl group-hover:scale-110 transition duration-300"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium mt-1 hidden md:block">Lưu</span>
        </a>

        {{-- View Detail Button --}}
        <a href="{{ route('wallpaper.show', $wallpaper->id) }}" class="flex flex-col items-center group">
            <div class="w-11 h-11 rounded-full bg-white border border-slate-200 text-slate-700 shadow-md flex items-center justify-center group-hover:bg-slate-100 group-hover:text-slate-900 transform transition-all duration-300">
                <i class="ph-fill ph-info text-xl group-hover:scale-110 transition duration-300"></i>
            </div>
            <span class="text-[10px] text-slate-600 font-medium mt-1 hidden md:block">Xem</span>
        </a>
    </div>

    {{-- Content Panel (Bottom Left) --}}
    <div class="absolute left-4 md:left-6 bottom-32 z-30 max-w-[75%] md:max-w-[60%]">
        <div class="flex items-center gap-2 mb-1.5">
            @if($wallpaper->category)
                <span class="inline-flex items-center px-2 py-0.5 bg-white shadow-sm text-slate-700 font-bold text-[9px] rounded-full border border-slate-200 uppercase tracking-wider">
                    {{ $wallpaper->category->name }}
                </span>
            @endif
        </div>
        
        <h2 class="text-sm md:text-lg font-bold text-slate-900 mb-1 tracking-wide leading-tight">{{ $wallpaper->name }}</h2>
        
        @if($wallpaper->description)
            <div class="relative">
                <p class="text-slate-600 text-[11px] md:text-xs line-clamp-2 transition-all duration-300 leading-relaxed" id="desc-{{ $wallpaper->id }}">
                    {{ $wallpaper->description }}
                </p>
                @if(mb_strlen($wallpaper->description) > 80)
                    <button onclick="toggleDescription({{ $wallpaper->id }}, this)" class="text-[var(--color-wh-accent)] hover:underline text-[10px] font-bold mt-1 transition duration-200 block">
                        Xem thêm
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>
@endforeach
