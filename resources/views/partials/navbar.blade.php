{{-- Navigation Bar --}}
<nav class="navbar" id="main-navbar">
    <div class="navbar-container">
        <a href="{{ route('home') }}" class="navbar-logo" id="nav-logo">
            <span class="navbar-logo-text">Wallpaper<span class="text-wh-accent">Hunt</span></span>
        </a>

        {{-- Desktop Navigation --}}
        <div class="navbar-links" id="nav-links">
            <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}" id="nav-home">
                <i class="ph ph-house"></i>
                <span>Trang chủ</span>
            </a>
            <a href="{{ route('feed') }}" class="navbar-link {{ request()->routeIs('feed') ? 'active' : '' }}" id="nav-feed">
                <i class="ph ph-sparkle"></i>
                <span>Feed</span>
            </a>
            <a href="{{ route('explore') }}" class="navbar-link {{ request()->routeIs('explore') ? 'active' : '' }}" id="nav-explore">
                <i class="ph ph-compass"></i>
                <span>Khám phá</span>
            </a>
            <a href="{{ route('categories.index') }}" class="navbar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" id="nav-categories">
                <i class="ph ph-squares-four"></i>
                <span>Danh mục</span>
            </a>
        </div>

        {{-- Search Bar (Desktop) --}}
        <div class="navbar-search relative" id="nav-search">
            <form action="{{ route('explore') }}" method="GET" class="navbar-search-form">
                <i class="ph ph-magnifying-glass navbar-search-icon"></i>
                <input
                    type="text"
                    name="q"
                    placeholder="Tìm kiếm wallpaper..."
                    value="{{ request('q') }}"
                    class="navbar-search-input"
                    id="nav-search-input"
                    autocomplete="off"
                    oninput="fetchSuggestions(this.value)"
                    onfocus="toggleSuggestions(true)"
                    onblur="setTimeout(() => toggleSuggestions(false), 200)"
                >
            </form>

            {{-- Search Suggestions Dropdown --}}
            <div id="search-suggestions" class="absolute left-0 right-0 top-full mt-2 bg-white border border-slate-200/40 rounded-2xl shadow-xl py-2 hidden z-50 max-h-60 overflow-y-auto">
                {{-- Loaded via JS --}}
            </div>
        </div>

        {{-- Right Actions --}}
        <div class="navbar-actions flex items-center gap-4">
            @if(session()->has('customer_id'))
                @php
                    $nav_customer = \App\Models\Customer::find(session('customer_id'));
                @endphp
                {{-- Notifications Bell --}}
                @php
                    $unread_notifications = \App\Models\CustomerNotification::where('customer_id', session('customer_id'))
                        ->where('is_read', false)
                        ->orderByDesc('created_at')
                        ->get();
                    $all_notifications = \App\Models\CustomerNotification::where('customer_id', session('customer_id'))
                        ->orderByDesc('created_at')
                        ->take(10)
                        ->get();
                @endphp
                <div class="relative group">
                    <button class="relative w-9 h-9 rounded-full bg-slate-50 border border-slate-200/60 flex items-center justify-center text-slate-600 hover:text-[var(--color-wh-accent)] hover:bg-slate-100 transition shadow-sm">
                        <i class="ph ph-bell text-lg"></i>
                        @if($unread_notifications->count() > 0)
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                        @endif
                    </button>

                    {{-- Notifications Dropdown --}}
                    <div class="absolute right-0 mt-2 w-72 bg-white border border-slate-200/60 rounded-2xl shadow-xl p-3 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 z-50">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-2 mb-2">
                            <h4 class="text-xs font-bold text-slate-800">Thông báo</h4>
                            @if($unread_notifications->count() > 0)
                                <form action="{{ route('notifications.read-all') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-[var(--color-wh-accent)] font-semibold hover:underline">Đọc tất cả</button>
                                </form>
                            @endif
                        </div>

                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            @forelse($all_notifications as $notif)
                                <div class="p-2 rounded-xl text-left transition duration-200 {{ $notif->is_read ? 'opacity-70 bg-transparent' : 'bg-slate-50/80 border-l-2 border-[var(--color-wh-accent)]' }}">
                                    <h5 class="text-xs font-bold text-slate-800 flex items-center gap-1">
                                        @if($notif->type === 'approval')
                                            <i class="ph-fill ph-check-circle text-green-500 text-sm"></i>
                                        @elseif($notif->type === 'rejection')
                                            <i class="ph-fill ph-warning-circle text-red-500 text-sm"></i>
                                        @else
                                            <i class="ph-fill ph-info text-blue-500 text-sm"></i>
                                        @endif
                                        {{ $notif->title }}
                                    </h5>
                                    <p class="text-[10px] text-slate-500 mt-0.5 leading-relaxed">{{ $notif->message }}</p>
                                    <span class="text-[8px] text-slate-400 block mt-1">{{ $notif->created_at->diffForHumans() }}</span>
                                </div>
                            @empty
                                <div class="text-center py-4 text-xs text-slate-400">
                                    Không có thông báo mới.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="relative group">
                    <button class="flex items-center gap-2 bg-slate-50 border border-slate-200/60 rounded-full p-1 pr-3 hover:bg-slate-100 hover:border-slate-300 transition duration-200 shadow-sm">
                        @if($nav_customer && $nav_customer->avatar)
                            <img src="{{ str_starts_with($nav_customer->avatar, 'http') ? $nav_customer->avatar : asset('storage/' . $nav_customer->avatar) }}" class="w-7 h-7 rounded-full object-cover" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(session('customer_name')) }}&background=random';">
                        @else
                            <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 text-sm">
                                <i class="ph ph-user"></i>
                            </div>
                        @endif
                        <span class="hidden md:inline text-xs font-bold text-slate-700 tracking-tight">{{ session('customer_name') }}</span>
                        <i class="ph ph-caret-down text-[10px] text-slate-400"></i>
                    </button>
                    
                    {{-- Dropdown Menu --}}
                    <div class="absolute right-0 mt-2 w-48 bg-white border border-slate-200/60 rounded-2xl shadow-xl py-2 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-200 z-50">
                        <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[var(--color-wh-accent)] transition">
                            <i class="ph ph-user-circle text-lg"></i>
                            <span>Trang cá nhân</span>
                        </a>
                        <a href="{{ route('profile.liked') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[var(--color-wh-accent)] transition">
                            <i class="ph ph-heart text-lg"></i>
                            <span>Ảnh đã thích</span>
                        </a>
                        <a href="{{ route('profile.uploaded') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-[var(--color-wh-accent)] transition">
                            <i class="ph ph-cloud-arrow-up text-lg"></i>
                            <span>Ảnh đã tải</span>
                        </a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="{{ route('logout') }}" class="flex items-center gap-3 px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 transition">
                            <i class="ph ph-sign-out text-lg"></i>
                            <span>Đăng xuất</span>
                        </a>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200/80 text-slate-800 font-bold text-xs px-4 py-2.5 rounded-full transition duration-200 shadow-sm border border-slate-200/30">
                    <i class="ph ph-user text-sm"></i>
                    <span>Đăng nhập</span>
                </a>
            @endif

            <x-button :href="route('upload')" variant="primary" class="flex items-center gap-1 px-4 py-2" id="nav-upload-btn">
                <i class="ph ph-upload-simple"></i>
                <span class="hidden sm:inline">Tải lên</span>
            </x-button>
        </div>

        {{-- Mobile Menu Toggle --}}
        <button class="navbar-mobile-toggle" id="mobile-menu-toggle" aria-label="Menu">
            <i class="ph ph-list"></i>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div class="navbar-mobile-menu" id="mobile-menu">
        <div class="navbar-mobile-search">
            <form action="{{ route('explore') }}" method="GET">
                <i class="ph ph-magnifying-glass"></i>
                <input type="text" name="q" placeholder="Tìm kiếm wallpaper..." value="{{ request('q') }}">
            </form>
        </div>
        <a href="{{ route('home') }}" class="navbar-mobile-link {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="ph ph-house"></i> Trang chủ
        </a>
        <a href="{{ route('explore') }}" class="navbar-mobile-link {{ request()->routeIs('explore') ? 'active' : '' }}">
            <i class="ph ph-compass"></i> Khám phá
        </a>
        <a href="{{ route('categories.index') }}" class="navbar-mobile-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="ph ph-squares-four"></i> Danh mục
        </a>
    </div>
</nav>

@push('scripts')
<script>
    let suggestionTimeout = null;
    
    function fetchSuggestions(query) {
        clearTimeout(suggestionTimeout);
        if (!query || query.length < 2) {
            document.getElementById('search-suggestions').classList.add('hidden');
            return;
        }

        suggestionTimeout = setTimeout(() => {
            fetch(`/api/search-suggestions?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    const dropdown = document.getElementById('search-suggestions');
                    if (data.length === 0) {
                        dropdown.innerHTML = `<div class="px-4 py-3 text-xs text-slate-400 text-center font-medium">Không tìm thấy gợi ý</div>`;
                    } else {
                        let html = '';
                        data.forEach(item => {
                            html += `
                                <a href="${item.url}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition duration-150">
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

    function toggleSuggestions(show) {
        const dropdown = document.getElementById('search-suggestions');
        const input = document.getElementById('nav-search-input');
        if (show && input.value.length >= 2 && dropdown.innerHTML.trim() !== '') {
            dropdown.classList.remove('hidden');
        } else if (!show) {
            dropdown.classList.add('hidden');
        }
    }

    // Mobile menu toggle
    document.getElementById('mobile-menu-toggle')?.addEventListener('click', () => {
        document.getElementById('mobile-menu')?.classList.toggle('open');
        const icon = document.querySelector('#mobile-menu-toggle i');
        icon.classList.toggle('ph-list');
        icon.classList.toggle('ph-x');
    });

    // Navbar scroll effect
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('main-navbar');
        const currentScroll = window.scrollY;
        if (currentScroll > 80) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
        lastScroll = currentScroll;
    });
</script>
@endpush
