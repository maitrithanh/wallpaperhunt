<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WallpaperHunt — Cộng đồng chia sẻ hình nền đẹp')</title>
    <meta name="description" content="@yield('meta_description', 'WallpaperHunt - Khám phá và tải hàng ngàn hình nền chất lượng cao miễn phí. Cộng đồng chia sẻ wallpaper lớn nhất Việt Nam.')">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    {{-- Phosphor Icons --}}
    <script src="https://unpkg.com/@phosphor-icons/web@2.1.1"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')

    {{-- Dynamic Styles from Admin Settings --}}
    <style>
        :root {
            --color-wh-accent: {{ \App\Models\Setting::get('primary_color', '#2563eb') }};
            --color-wh-accent-2: {{ \App\Models\Setting::get('accent_color', '#06b6d4') }};
        }
    </style>

    @if($favicon = \App\Models\Setting::get('site_favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . $favicon) }}">
    @endif
</head>
<body class="bg-wh-dark text-slate-900 font-sans antialiased min-h-screen flex flex-col">
    {{-- Navbar (Hidden on mobile) --}}
    <div class="hidden md:block">
        @include('partials.navbar')
    </div>

    {{-- Main Content --}}
    <main class="flex-1 pb-20 md:pb-0">
        @yield('content')
    </main>

    {{-- Footer (Hidden on mobile or feed) --}}
    @if(!request()->routeIs('feed'))
        <div class="hidden md:block">
            @include('partials.footer')
        </div>
    @endif

    {{-- iPhone-style Global Bottom Navigation Bar for Mobile --}}
    <div id="mobile-bottom-nav" class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-2xl border-t border-slate-200/80 pt-3 pb-4 flex flex-col items-center gap-3 shadow-[0_-10px_30px_rgba(15,23,42,0.06)]">
        <div class="flex items-center gap-6 w-[95%] max-w-md justify-around">
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('home') ? 'text-[var(--color-wh-accent)]' : 'text-slate-500' }}">
                <i class="{{ request()->routeIs('home') ? 'ph-fill' : 'ph' }} ph-house text-2xl"></i>
                <span class="text-[9px] font-bold">Trang chủ</span>
            </a>
            <a href="{{ route('explore') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('explore') ? 'text-[var(--color-wh-accent)]' : 'text-slate-500' }}">
                <i class="{{ request()->routeIs('explore') ? 'ph-fill' : 'ph' }} ph-compass text-2xl"></i>
                <span class="text-[9px] font-bold">Khám phá</span>
            </a>
            <a href="{{ route('feed') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('feed') ? 'text-[var(--color-wh-accent)]' : 'text-slate-500' }}">
                <i class="{{ request()->routeIs('feed') ? 'ph-fill' : 'ph' }} ph-film-strip text-2xl"></i>
                <span class="text-[9px] font-bold">Feed</span>
            </a>
            <a href="{{ route('upload') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('upload') ? 'text-[var(--color-wh-accent)]' : 'text-slate-500' }}">
                <i class="{{ request()->routeIs('upload') ? 'ph-fill' : 'ph' }} ph-plus-circle text-2xl"></i>
                <span class="text-[9px] font-bold">Tải lên</span>
            </a>
            <a href="{{ route('profile') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('profile') ? 'text-[var(--color-wh-accent)]' : 'text-slate-500' }}">
                <i class="{{ request()->routeIs('profile') ? 'ph-fill' : 'ph' }} ph-user text-2xl"></i>
                <span class="text-[9px] font-bold">Hồ sơ</span>
            </a>
        </div>
    </div>

    {{-- Toast Notifications --}}
    <div id="toast-container" class="fixed bottom-6 right-6 z-[9999] flex flex-col gap-3 pointer-events-none"></div>

    @stack('scripts')

    <script>
        // CSRF token for AJAX
        window.csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Toast notification helper
        window.showToast = function(message, type = 'success') {
            const toastId = 'toast-' + Date.now();
            const container = document.getElementById('toast-container');
            if (!container) return;

            let bgColor = 'bg-slate-900/95 backdrop-blur-md border border-red-500/20';
            let icon = '<i class="ph-fill ph-warning-circle text-red-500 text-lg"></i>';
            
            if (type === 'success') {
                bgColor = 'bg-slate-900/95 backdrop-blur-md border border-green-500/20';
                icon = '<i class="ph-fill ph-check-circle text-green-500 text-lg"></i>';
            }

            const toastHtml = `
                <div id="${toastId}" class="flex items-center gap-3 ${bgColor} text-white px-5 py-3.5 rounded-2xl shadow-xl transform translate-y-10 opacity-0 transition-all duration-300">
                    ${icon}
                    <span class="text-xs font-bold tracking-wide">${message}</span>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', toastHtml);
            const toastEl = document.getElementById(toastId);
            
            setTimeout(() => {
                if (toastEl) toastEl.classList.remove('translate-y-10', 'opacity-0');
            }, 50);

            setTimeout(() => {
                if (toastEl) {
                    toastEl.classList.add('opacity-0');
                    setTimeout(() => toastEl.remove(), 300);
                }
            }, 4000);
        };
        @if(session('success'))
            window.showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            window.showToast("{{ session('error') }}", 'error');
        @endif
    </script>
</body>
</html>
