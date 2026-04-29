@extends('layouts.app')
@section('title', 'Tài khoản của tôi — WallpaperHunt')

@section('content')
<div class="bg-slate-50 min-h-screen text-slate-900 antialiased font-sans pb-16 pt-24 md:pt-32">
    <div class="max-w-xl mx-auto px-4">
        
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10">
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Cài đặt tài khoản</h1>
                <p class="text-xs text-slate-500">Cập nhật thông tin cá nhân và mật khẩu của bạn.</p>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-start gap-2 text-sm font-medium">
                    <i class="ph-fill ph-check-circle text-xl flex-shrink-0 mt-0.5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-start gap-2 text-sm font-medium">
                    <i class="ph-fill ph-warning-circle text-xl flex-shrink-0 mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Avatar Upload Section --}}
                <div class="flex flex-col items-center gap-4 mb-6">
                    <div class="relative group">
                        @if($customer->avatar)
                            <img id="avatar-preview" src="{{ str_starts_with($customer->avatar, 'http') ? $customer->avatar : asset('storage/' . $customer->avatar) }}" alt="{{ $customer->full_name }}" 
                                 class="w-24 h-24 rounded-full object-cover border-4 border-slate-50 shadow-md bg-slate-100"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($customer->full_name) }}&background=random';">
                        @else
                            <div id="avatar-placeholder" class="w-24 h-24 rounded-full bg-slate-100 border-4 border-slate-50 shadow-md flex items-center justify-center text-slate-400 text-3xl">
                                <i class="ph ph-user"></i>
                            </div>
                        @endif
                        <label for="avatar" class="absolute bottom-0 right-0 bg-white border border-slate-200 text-slate-600 rounded-full p-2 cursor-pointer shadow-md hover:bg-slate-50 hover:text-[var(--color-wh-accent)] transition duration-200">
                            <i class="ph-bold ph-pencil-simple text-sm"></i>
                        </label>
                    </div>
                    <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="previewImage(event)">
                    <span class="text-xs font-semibold text-slate-500">Bấm nút bút chì để tải lên ảnh đại diện</span>
                </div>

                {{-- Full Name --}}
                <div>
                    <label for="full_name" class="block text-sm font-semibold text-slate-700 mb-2">Họ và tên</label>
                    <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                        <i class="ph ph-user text-slate-400 text-xl ml-2 mr-3"></i>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $customer->full_name) }}" required
                               placeholder="Họ và tên của bạn" class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                    <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                        <i class="ph ph-envelope text-slate-400 text-xl ml-2 mr-3"></i>
                        <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                               placeholder="name@example.com" class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                    </div>
                </div>

                <div class="border-t border-slate-100 my-6 pt-4">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Đổi mật khẩu (Bỏ trống nếu không đổi)</p>
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Mật khẩu mới</label>
                    <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                        <i class="ph ph-lock text-slate-400 text-xl ml-2 mr-3"></i>
                        <input type="password" name="password" id="password" placeholder="••••••••"
                               class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                    </div>
                </div>

                {{-- Password Confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Xác nhận mật khẩu mới</label>
                    <div class="relative flex items-center bg-slate-50 border border-slate-200 rounded-2xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white focus-within:shadow-sm transition duration-200">
                        <i class="ph ph-lock text-slate-400 text-xl ml-2 mr-3"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                               class="w-full bg-transparent text-slate-800 placeholder-slate-400 focus:outline-none text-sm">
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4 flex items-center gap-3">
                    <x-button type="submit" variant="primary" class="flex-1 py-3 px-6 flex items-center justify-center gap-2">
                        <i class="ph ph-floppy-disk text-xl"></i>
                        <span>Lưu thay đổi</span>
                    </x-button>
                    @php
                        $partner = \App\Models\Partner::where('email', $customer->email)->first();
                    @endphp
                    @if($partner)
                        <a href="{{ route('artist.show', $partner->id) }}" class="px-4 py-3 bg-slate-100 border border-slate-200 text-slate-700 hover:bg-slate-200 text-sm font-semibold rounded-full flex items-center gap-2 transition duration-200">
                            <i class="ph ph-eye"></i>
                            <span>Xem trang public</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (placeholder) {
                    // Replace placeholder div with an img
                    const img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.className = 'w-24 h-24 rounded-full object-cover border-4 border-slate-50 shadow-md bg-slate-100';
                    placeholder.parentNode.replaceChild(img, placeholder);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
