@extends('layouts.app')
@section('title', $artist->full_name . ' — WallpaperHunt')

@section('content')
<div class="bg-slate-50 min-h-screen text-slate-900 antialiased font-sans pb-16 pt-24 md:pt-32">
    <div class="max-w-5xl mx-auto px-4 text-center">

        {{-- Alerts --}}
        @if(session('success'))
            <div class="max-w-xl mx-auto mb-6 p-4 bg-green-50 border border-green-100 text-green-700 rounded-2xl flex items-start gap-2 text-sm font-medium text-left">
                <i class="ph-fill ph-check-circle text-xl flex-shrink-0 mt-0.5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="max-w-xl mx-auto mb-6 p-4 bg-red-50 border border-red-100 text-red-600 rounded-2xl flex items-start gap-2 text-sm font-medium text-left">
                <i class="ph-fill ph-warning-circle text-xl flex-shrink-0 mt-0.5"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Minimal Profile Header --}}
        <div class="flex flex-col items-center mb-16">
            {{-- Avatar --}}
            @php
                $artistCustomer = \App\Models\Customer::where('email', $artist->email)->first();
                $artistAvatar = $artist->avatar;
                if (!$artistAvatar && $artistCustomer && $artistCustomer->avatar) {
                    $artistAvatar = $artistCustomer->avatar;
                }
            @endphp
            <div class="relative mb-6">
                @if($artistAvatar)
                    <img src="{{ asset('storage/' . $artistAvatar) }}" alt="{{ $artist->full_name }}" 
                         class="w-24 h-24 md:w-28 md:h-28 rounded-full object-cover shadow-sm bg-slate-100">
                @else
                    <div class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-slate-200 flex items-center justify-center text-slate-400 text-3xl">
                        <i class="ph ph-user"></i>
                    </div>
                @endif
                <div class="absolute bottom-1 right-1 bg-blue-600 text-white rounded-full w-6 h-6 border-2 border-white flex items-center justify-center shadow-md" title="Nghệ sĩ chính thức">
                    <i class="ph-fill ph-circle-wavy-check text-sm"></i>
                </div>
            </div>

            {{-- Name & Bio --}}
            <h1 class="text-3xl md:text-4xl font-black text-slate-900 mb-2 tracking-tight">{{ $artist->full_name }}</h1>
            <p class="text-sm text-slate-500 font-medium mb-4 flex items-center justify-center gap-1">
                <i class="ph ph-map-pin"></i>
                Cộng đồng WallpaperHunt
            </p>

            {{-- Clean Flat Stats --}}
            <div class="flex items-center gap-6 text-sm font-medium text-slate-600 bg-white border border-slate-200/50 rounded-full px-6 py-2 shadow-sm">
                <span><strong class="text-slate-900">{{ number_format($stats['total_wallpapers']) }}</strong> Tác phẩm</span>
                <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                <span><strong class="text-slate-900">{{ number_format($stats['total_views']) }}</strong> Lượt xem</span>
                <span class="w-1.5 h-1.5 bg-slate-200 rounded-full"></span>
                <span><strong class="text-slate-900">{{ number_format($stats['total_likes']) }}</strong> Yêu thích</span>
            </div>

            {{-- Operational Tools (Owner Only) --}}
            @if($isOwner)
            <div class="flex items-center gap-3 mt-6">
                <button onclick="openModal('edit-profile-modal')" class="px-4 py-2 bg-slate-900 text-white hover:bg-slate-800 text-xs font-semibold rounded-full flex items-center gap-2 transition duration-200 shadow">
                    <i class="ph ph-pencil-simple"></i>
                    <span>Chỉnh sửa hồ sơ</span>
                </button>
                <button onclick="openModal('change-password-modal')" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-semibold rounded-full flex items-center gap-2 transition duration-200 shadow-sm">
                    <i class="ph ph-lock"></i>
                    <span>Đổi mật khẩu</span>
                </button>
            </div>
            @endif
        </div>

        {{-- Divider --}}
        <div class="border-t border-slate-200/60 mb-12"></div>

        {{-- Wallpapers Grid --}}
        <div class="text-left">
            @if($wallpapers->count() > 0)
                <x-wallpaper-grid :wallpapers="$wallpapers" />
                
                <div class="mt-12 flex justify-center">
                    {{ $wallpapers->links() }}
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-100 p-12 text-center shadow-sm max-w-md mx-auto">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 text-3xl">
                        <i class="ph ph-image-broken"></i>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">Chưa có tác phẩm</h3>
                    <p class="text-xs text-slate-500">Bạn hiện chưa công khai hình nền nào.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if($isOwner)
{{-- Edit Profile Modal --}}
<div id="edit-profile-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" onclick="closeModal('edit-profile-modal')"></div>
    <div class="relative bg-white rounded-3xl w-full max-w-md p-6 md:p-8 shadow-2xl border border-slate-100 transform transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900">Chỉnh sửa hồ sơ</h3>
            <button onclick="closeModal('edit-profile-modal')" class="text-slate-400 hover:text-slate-600"><i class="ph ph-x text-2xl"></i></button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
            @csrf
            
            {{-- Avatar preview/upload --}}
            <div class="flex flex-col items-center gap-3 mb-4">
                <div class="relative">
                    @if($customer->avatar)
                        <img id="modal-avatar-preview" src="{{ asset('storage/' . $customer->avatar) }}" alt="" class="w-20 h-20 rounded-full object-cover border border-slate-200 shadow-sm">
                    @else
                        <div id="modal-avatar-placeholder" class="w-20 h-20 rounded-full bg-slate-100 border border-slate-200 shadow-sm flex items-center justify-center text-slate-400 text-2xl">
                            <i class="ph ph-user"></i>
                        </div>
                    @endif
                    <label for="modal-avatar" class="absolute bottom-0 right-0 bg-white border border-slate-200 text-slate-600 rounded-full p-1.5 cursor-pointer shadow hover:bg-slate-50">
                        <i class="ph-bold ph-pencil-simple text-xs"></i>
                    </label>
                </div>
                <input type="file" name="avatar" id="modal-avatar" class="hidden" accept="image/*" onchange="previewModalImage(event)">
                <span class="text-[10px] text-slate-500 font-medium">Thay đổi ảnh đại diện (Tối đa 4MB)</span>
            </div>

            <div>
                <label for="modal-full_name" class="block text-xs font-bold text-slate-700 mb-1">Họ và tên</label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white transition duration-200">
                    <input type="text" name="full_name" id="modal-full_name" value="{{ $customer->full_name }}" required class="w-full bg-transparent text-slate-800 focus:outline-none text-sm">
                </div>
            </div>

            <div>
                <label for="modal-email" class="block text-xs font-bold text-slate-700 mb-1">Email</label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white transition duration-200">
                    <input type="email" name="email" id="modal-email" value="{{ $customer->email }}" required class="w-full bg-transparent text-slate-800 focus:outline-none text-sm">
                </div>
            </div>

            <x-button type="submit" variant="primary" class="w-full py-3 mt-4 flex items-center justify-center gap-2">
                <i class="ph ph-floppy-disk"></i>
                <span>Lưu thay đổi</span>
            </x-button>
        </form>
    </div>
</div>

{{-- Change Password Modal --}}
<div id="change-password-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm" onclick="closeModal('change-password-modal')"></div>
    <div class="relative bg-white rounded-3xl w-full max-w-md p-6 md:p-8 shadow-2xl border border-slate-100 transform transition-all duration-300">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900">Đổi mật khẩu</h3>
            <button onclick="closeModal('change-password-modal')" class="text-slate-400 hover:text-slate-600"><i class="ph ph-x text-2xl"></i></button>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4 text-left">
            @csrf
            {{-- Pass standard fields as hidden so validation doesn't fail --}}
            <input type="hidden" name="full_name" value="{{ $customer->full_name }}">
            <input type="hidden" name="email" value="{{ $customer->email }}">

            <div>
                <label for="modal-password" class="block text-xs font-bold text-slate-700 mb-1">Mật khẩu mới</label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white transition duration-200">
                    <input type="password" name="password" id="modal-password" required placeholder="••••••••" class="w-full bg-transparent text-slate-800 focus:outline-none text-sm">
                </div>
            </div>

            <div>
                <label for="modal-password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Xác nhận mật khẩu mới</label>
                <div class="flex items-center bg-slate-50 border border-slate-200 rounded-xl p-3 focus-within:border-[var(--color-wh-accent)] focus-within:bg-white transition duration-200">
                    <input type="password" name="password_confirmation" id="modal-password_confirmation" required placeholder="••••••••" class="w-full bg-transparent text-slate-800 focus:outline-none text-sm">
                </div>
            </div>

            <x-button type="submit" variant="primary" class="w-full py-3 mt-4 flex items-center justify-center gap-2">
                <i class="ph ph-lock-key"></i>
                <span>Đổi mật khẩu</span>
            </x-button>
        </form>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function previewModalImage(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('modal-avatar-preview');
                const placeholder = document.getElementById('modal-avatar-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                } else if (placeholder) {
                    const img = document.createElement('img');
                    img.id = 'modal-avatar-preview';
                    img.src = e.target.result;
                    img.className = 'w-20 h-20 rounded-full object-cover border border-slate-200 shadow-sm';
                    placeholder.parentNode.replaceChild(img, placeholder);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endif
