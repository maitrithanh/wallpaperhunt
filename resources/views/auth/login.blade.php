@extends('layouts.app')
@section('title', 'Đăng nhập — WallpaperHunt')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/80 border border-slate-100 p-8 md:p-10 transform transition-all duration-300">
        {{-- Logo & Header --}}
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <span class="text-2xl font-black text-slate-900">Wallpaper<span class="text-[var(--color-wh-accent)]">Hunt</span></span>
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Đăng nhập</h1>
            <p class="text-slate-500 text-sm">Chào mừng bạn quay trở lại với cộng đồng</p>
        </div>

        {{-- Errors --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-start gap-2 text-sm">
                <i class="ph ph-warning-circle text-lg mt-0.5 flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('customer.login') }}" class="space-y-6">
            @csrf
            
            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email của bạn</label>
                <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                    <i class="ph ph-envelope text-slate-400 text-xl ml-2 mr-3"></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           placeholder="name@example.com" class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                </div>
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Mật khẩu</label>
                <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                    <i class="ph ph-lock text-slate-400 text-xl ml-2 mr-3"></i>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                           class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                    <button type="button" id="toggle-password" class="text-slate-400 hover:text-slate-600 px-2">
                        <i class="ph ph-eye text-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Submit --}}
            <x-button type="submit" variant="primary" class="w-full py-4 px-6 flex items-center justify-center gap-2">
                <i class="ph ph-sign-in text-xl"></i>
                <span>Đăng nhập</span>
            </x-button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-500 text-sm">
                Chưa có tài khoản? 
                <a href="{{ route('customer.register.form') }}" class="font-bold text-[var(--color-wh-accent)] hover:opacity-80 ml-1">Đăng ký ngay</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('toggle-password')?.addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'ph ph-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'ph ph-eye';
        }
    });
</script>
@endpush
