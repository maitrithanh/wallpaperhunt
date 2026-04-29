@extends('layouts.app')
@section('title', 'Đăng ký — WallpaperHunt')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4 py-12">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/80 border border-slate-100 p-8 md:p-10 transform transition-all duration-300">
        {{-- Logo & Header --}}
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mb-6">
                <span class="text-2xl font-black text-slate-900">Wallpaper<span class="text-[var(--color-wh-accent)]">Hunt</span></span>
            </a>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Đăng ký</h1>
            <p class="text-slate-500 text-sm">Tham gia chia sẻ và khám phá wallpaper miễn phí</p>
        </div>

        {{-- Errors --}}
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-start gap-2 text-sm">
                <i class="ph ph-warning-circle text-lg mt-0.5 flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('customer.register') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            {{-- Full Name --}}
            <div>
                <label for="full_name" class="block text-sm font-semibold text-slate-700 mb-2">Họ và tên</label>
                <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                    <i class="ph ph-user text-slate-400 text-xl ml-2 mr-3"></i>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required autofocus
                           placeholder="Họ và tên của bạn" class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                </div>
            </div>

            {{-- Avatar --}}
            <div>
                <label for="avatar" class="block text-sm font-semibold text-slate-700 mb-2">Ảnh đại diện (Tùy chọn)</label>
                <div class="flex items-center gap-4 mb-3">
                    <div id="register-avatar-placeholder" class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl border border-slate-200">
                        <i class="ph ph-user"></i>
                    </div>
                    <img id="register-avatar-preview" class="w-12 h-12 rounded-full object-cover hidden border border-slate-200 shadow-sm" alt="Preview">
                    <div class="relative flex-1 flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                        <i class="ph ph-image text-slate-400 text-xl ml-2 mr-3"></i>
                        <input type="file" name="avatar" id="avatar" accept="image/*" onchange="previewRegisterAvatar(event)"
                               class="w-full bg-transparent text-slate-800 focus:outline-none text-sm file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[var(--color-wh-accent)]/10 file:text-[var(--color-wh-accent)] hover:file:bg-[var(--color-wh-accent)]/20 cursor-pointer">
                    </div>
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Hỗ trợ định dạng ảnh tối đa 4MB.</p>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email của bạn</label>
                <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                    <i class="ph ph-envelope text-slate-400 text-xl ml-2 mr-3"></i>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
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
                </div>
            </div>

            {{-- Password Confirmation --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Xác nhận mật khẩu</label>
                <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                    <i class="ph ph-lock text-slate-400 text-xl ml-2 mr-3"></i>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                           class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                </div>
            </div>

            {{-- Submit --}}
            <x-button type="submit" variant="primary" class="w-full py-4 px-6 flex items-center justify-center gap-2">
                <i class="ph ph-user-plus text-xl"></i>
                <span>Đăng ký</span>
            </x-button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 text-center border-t border-slate-100 pt-6">
            <p class="text-slate-500 text-sm">
                Đã có tài khoản? 
                <a href="{{ route('login') }}" class="font-bold text-[var(--color-wh-accent)] hover:opacity-80 ml-1">Đăng nhập ngay</a>
            </p>
        </div>
    </div>
</div>

<script>
    function previewRegisterAvatar(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('register-avatar-preview');
                const placeholder = document.getElementById('register-avatar-placeholder');
                
                if (preview && placeholder) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
