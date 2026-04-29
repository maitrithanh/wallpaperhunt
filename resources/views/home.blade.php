@extends('layouts.app')

@section('title', 'WallpaperHunt — Cộng đồng chia sẻ hình nền chất lượng cao')

@section('content')
<div class="bg-slate-50 min-h-screen text-slate-900 antialiased font-sans">
    
    {{-- Premium Hero Section --}}
    <section class="relative pt-32 pb-24 px-4 overflow-hidden bg-white border-b border-slate-100 flex items-center justify-center min-h-[500px]">
        
        {{-- Slider Background Overlay --}}
        <div class="absolute inset-0 z-0 opacity-[0.06] pointer-events-none flex flex-col justify-center gap-6 overflow-hidden">
            {{-- Row 1 --}}
            <div class="animate-marquee flex gap-6">
                @forelse($trendingWallpapers->take(6) as $wall)
                    <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                        <img src="{{ $wall->src ? (str_starts_with($wall->src, 'http') ? $wall->src : asset('storage/' . $wall->src)) : 'https://picsum.photos/seed/' . $wall->id . '/800/600' }}" alt="" class="w-full h-full object-cover rounded-3xl">
                    </div>
                @empty
                    @foreach([1, 2, 3, 4, 5, 6] as $i)
                        <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                            <img src="https://picsum.photos/seed/row1_{{ $i }}/800/600" alt="" class="w-full h-full object-cover rounded-3xl">
                        </div>
                    @endforeach
                @endforelse
                @forelse($trendingWallpapers->take(6) as $wall)
                    <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                        <img src="{{ $wall->src ? (str_starts_with($wall->src, 'http') ? $wall->src : asset('storage/' . $wall->src)) : 'https://picsum.photos/seed/' . $wall->id . '/800/600' }}" alt="" class="w-full h-full object-cover rounded-3xl">
                    </div>
                @empty
                    @foreach([1, 2, 3, 4, 5, 6] as $i)
                        <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                            <img src="https://picsum.photos/seed/row1_{{ $i }}/800/600" alt="" class="w-full h-full object-cover rounded-3xl">
                        </div>
                    @endforeach
                @endforelse
            </div>

            {{-- Row 2 --}}
            <div class="animate-marquee-reverse flex gap-6">
                @forelse($trendingWallpapers->skip(6)->take(6) as $wall)
                    <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                        <img src="{{ $wall->src ? (str_starts_with($wall->src, 'http') ? $wall->src : asset('storage/' . $wall->src)) : 'https://picsum.photos/seed/' . $wall->id . '/800/600' }}" alt="" class="w-full h-full object-cover rounded-3xl">
                    </div>
                @empty
                    @foreach([7, 8, 9, 10, 11, 12] as $i)
                        <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                            <img src="https://picsum.photos/seed/row2_{{ $i }}/800/600" alt="" class="w-full h-full object-cover rounded-3xl">
                        </div>
                    @endforeach
                @endforelse
                @forelse($trendingWallpapers->skip(6)->take(6) as $wall)
                    <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                        <img src="{{ $wall->src ? (str_starts_with($wall->src, 'http') ? $wall->src : asset('storage/' . $wall->src)) : 'https://picsum.photos/seed/' . $wall->id . '/800/600' }}" alt="" class="w-full h-full object-cover rounded-3xl">
                    </div>
                @empty
                    @foreach([7, 8, 9, 10, 11, 12] as $i)
                        <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                            <img src="https://picsum.photos/seed/row2_{{ $i }}/800/600" alt="" class="w-full h-full object-cover rounded-3xl">
                        </div>
                    @endforeach
                @endforelse
            </div>

            {{-- Row 3 --}}
            <div class="animate-marquee flex gap-6">
                @forelse($trendingWallpapers->skip(12)->take(6) as $wall)
                    <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                        <img src="{{ $wall->src ? (str_starts_with($wall->src, 'http') ? $wall->src : asset('storage/' . $wall->src)) : 'https://picsum.photos/seed/' . $wall->id . '/800/600' }}" alt="" class="w-full h-full object-cover rounded-3xl">
                    </div>
                @empty
                    @foreach([13, 14, 15, 16, 17, 18] as $i)
                        <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                            <img src="https://picsum.photos/seed/row3_{{ $i }}/800/600" alt="" class="w-full h-full object-cover rounded-3xl">
                        </div>
                    @endforeach
                @endforelse
                @forelse($trendingWallpapers->skip(12)->take(6) as $wall)
                    <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                        <img src="{{ $wall->src ? (str_starts_with($wall->src, 'http') ? $wall->src : asset('storage/' . $wall->src)) : 'https://picsum.photos/seed/' . $wall->id . '/800/600' }}" alt="" class="w-full h-full object-cover rounded-3xl">
                    </div>
                @empty
                    @foreach([13, 14, 15, 16, 17, 18] as $i)
                        <div class="w-72 md:w-[350px] h-48 md:h-56 flex-shrink-0">
                            <img src="https://picsum.photos/seed/row3_{{ $i }}/800/600" alt="" class="w-full h-full object-cover rounded-3xl">
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>

        {{-- Gradient fade overlay to protect text contrast --}}
        <div class="absolute inset-0 bg-gradient-to-b from-white/80 via-white/40 to-white z-5 pointer-events-none"></div>

        <style>
            @keyframes marquee {
                0% { transform: translateX(0%); }
                100% { transform: translateX(-50%); }
            }
            @keyframes marquee-reverse {
                0% { transform: translateX(-50%); }
                100% { transform: translateX(0%); }
            }
            .animate-marquee {
                display: flex;
                width: max-content;
                animation: marquee 60s linear infinite;
            }
            .animate-marquee-reverse {
                display: flex;
                width: max-content;
                animation: marquee-reverse 60s linear infinite;
            }
        </style>

        <div class="max-w-5xl mx-auto text-center relative z-10">
            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--color-wh-accent)]/10 border border-[var(--color-wh-accent)]/20 text-[var(--color-wh-accent)] font-semibold text-xs mb-8">
                <i class="ph-fill ph-sparkle"></i>
                <span>Cộng đồng Chia sẻ Wallpaper lớn nhất</span>
            </div>
            
            {{-- Title --}}
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-6 leading-[1.1]">
                Khám phá & Tải xuống<br>
                <span class="text-[var(--color-wh-accent)]">Hình nền cực nét</span>
            </h1>
            
            {{-- Subtitle --}}
            <p class="text-lg md:text-xl text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Nâng tầm trải nghiệm màn hình với hàng ngàn wallpaper chất lượng cao được thiết kế tỉ mỉ bởi cộng đồng nghệ sĩ tài năng.
            </p>

            {{-- Search Bar --}}
            <form action="{{ route('explore') }}" method="GET" class="max-w-2xl mx-auto mb-10 relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-[var(--color-wh-accent)]/10 to-[var(--color-wh-accent-2)]/10 rounded-2xl blur-lg opacity-60"></div>
                <div class="relative flex items-center bg-white/90 backdrop-blur-xl rounded-2xl p-1.5 border border-slate-200/50 shadow-xl shadow-slate-200/30 transition-all duration-300 focus-within:shadow-[var(--color-wh-accent)]/10 focus-within:border-[var(--color-wh-accent)]/40 focus-within:bg-white">
                    <i class="ph-fill ph-magnifying-glass text-xl text-[var(--color-wh-accent)] ml-4"></i>
                    <input type="text" id="home-search-input" name="q" placeholder="Tìm kiếm wallpaper..." autocomplete="off"
                           oninput="fetchHomeSuggestions(this.value)" onfocus="toggleHomeSuggestions(true)" onblur="setTimeout(() => toggleHomeSuggestions(false), 200)"
                           class="w-full py-3.5 px-4 text-slate-800 placeholder-slate-400 bg-transparent focus:outline-none text-sm font-medium">
                    <x-button type="submit" variant="primary" class="px-6 py-3 flex items-center gap-2 whitespace-nowrap !rounded-xl">
                        <span>Tìm kiếm</span>
                        <i class="ph-fill ph-paper-plane-right"></i>
                    </x-button>
                </div>

                {{-- Search Suggestions Dropdown --}}
                <div id="home-search-suggestions" class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200/40 rounded-2xl shadow-xl py-2 hidden z-50 max-h-60 overflow-y-auto">
                    {{-- Loaded via JS --}}
                </div>
            </form>

            {{-- Quick Tags --}}
            <div class="flex items-center justify-center flex-wrap gap-2 text-sm text-slate-500">
                <span class="font-medium mr-2">Chủ đề phổ biến:</span>
                @foreach($popularTags as $tag)
                    <a href="{{ route('explore', ['q' => $tag]) }}" class="px-4 py-1.5 bg-slate-50 hover:bg-[var(--color-wh-accent)]/10 hover:text-[var(--color-wh-accent)] border border-slate-200 hover:border-[var(--color-wh-accent)]/20 rounded-full font-medium transition duration-200">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Categories Grid --}}
    <section class="py-20 px-4 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph ph-squares-four text-[var(--color-wh-accent)]"></i>
                    Danh mục nổi bật
                </h2>
                <p class="text-slate-500 mt-1">Lọc nhanh hình nền theo sở thích của bạn</p>
            </div>
            @if($categories->count() > 0)
            <a href="{{ route('categories.index') }}" class="inline-flex items-center gap-1 font-semibold text-[var(--color-wh-accent)] hover:opacity-80 text-sm group">
                <span>Xem tất cả</span>
                <i class="ph ph-arrow-right transform transition group-hover:translate-x-1"></i>
            </a>
            @endif
        </div>

        @if($categories->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('category.show', $category->slug) }}" class="group relative aspect-square rounded-3xl overflow-hidden shadow-md shadow-slate-100 border border-slate-100 flex flex-col justify-end p-4 bg-slate-900 transform transition-all duration-300 hover:scale-[1.03] hover:shadow-lg">
                        @if($category->avatar)
                            <img src="{{ asset('storage/' . $category->avatar) }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-110 transition duration-500" onerror="this.onerror=null; this.src='https://picsum.photos/seed/{{ $category->id }}/300/300';">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-tr from-slate-900 to-[var(--color-wh-accent)] opacity-80 group-hover:scale-110 transition duration-500"></div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                        <div class="relative z-10 text-white">
                            <h3 class="font-bold text-lg leading-tight">{{ $category->name }}</h3>
                            <span class="text-xs opacity-75">{{ $category->photos_count ?? 0 }} hình nền</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                <i class="ph ph-folder-open text-slate-300 text-5xl mb-4 block"></i>
                <h3 class="font-bold text-slate-600 text-lg">Chưa có danh mục nào</h3>
                <p class="text-slate-400 text-sm mt-1">Vui lòng đăng nhập quyền quản trị để khởi tạo dữ liệu.</p>
            </div>
        @endif
    </section>

    {{-- Masonry Wallpapers --}}
    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-4">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="ph-fill ph-fire text-amber-500"></i>
                    Đang thịnh hành (Trending)
                </h2>
                <p class="text-slate-500 mt-1">Những tác phẩm được yêu thích nhất tuần qua</p>
            </div>
            @if($trendingWallpapers->count() > 0)
            <a href="{{ route('explore', ['sort' => 'popular']) }}" class="inline-flex items-center gap-1 font-semibold text-[var(--color-wh-accent)] hover:opacity-80 text-sm group">
                <span>Xem thêm</span>
                <i class="ph ph-arrow-right transform transition group-hover:translate-x-1"></i>
            </a>
            @endif
        </div>

        @if($trendingWallpapers->count() > 0)
            {{-- Masonry Grid --}}
            <x-wallpaper-grid :wallpapers="$trendingWallpapers" />
        @else
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm">
                <i class="ph ph-image-broken text-slate-300 text-5xl mb-4 block"></i>
                <h3 class="font-bold text-slate-600 text-lg">Chưa có hình nền nào khả dụng</h3>
                <p class="text-slate-400 text-sm mt-1">Duyệt ảnh hoặc tải ảnh lên để hiển thị dữ liệu.</p>
            </div>
        @endif
    </section>

</div>

<script>
    let homeSuggestionTimeout = null;

    function fetchHomeSuggestions(query) {
        clearTimeout(homeSuggestionTimeout);
        if (query.length < 2) {
            document.getElementById('home-search-suggestions').classList.add('hidden');
            return;
        }

        homeSuggestionTimeout = setTimeout(() => {
            fetch(`/api/search-suggestions?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    const dropdown = document.getElementById('home-search-suggestions');
                    if (data.length === 0) {
                        dropdown.innerHTML = `<div class="px-4 py-3 text-xs text-slate-400 text-center font-medium">Không tìm thấy gợi ý</div>`;
                    } else {
                        let html = '';
                        data.forEach(item => {
                            html += `
                                <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition duration-150 text-left">
                                    <img src="${item.src}" class="w-8 h-12 object-cover rounded-lg shadow-sm bg-slate-100" alt="">
                                    <span class="text-xs font-bold text-slate-800 truncate">${item.name}</span>
                                </a>
                            `;
                        });
                        dropdown.innerHTML = html;
                    }
                    dropdown.classList.remove('hidden');
                });
        }, 300);
    }

    function toggleHomeSuggestions(show) {
        const dropdown = document.getElementById('home-search-suggestions');
        const input = document.getElementById('home-search-input');
        if (show && input.value.length >= 2 && dropdown.innerHTML.trim() !== '') {
            dropdown.classList.remove('hidden');
        } else if (!show) {
            dropdown.classList.add('hidden');
        }
    }
</script>
@endsection
