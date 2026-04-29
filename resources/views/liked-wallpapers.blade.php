@extends('layouts.app')

@section('title', 'Ảnh đã thích — WallpaperHunt')

@section('content')
<div class="bg-slate-50 min-h-screen text-slate-900 antialiased font-sans relative pt-32 pb-20 px-4 md:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col items-center mb-12">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-2xl shadow-sm mb-4">
                <i class="ph-fill ph-heart"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Ảnh đã thích</h1>
            <p class="text-sm text-slate-500 font-medium">Danh sách các tác phẩm nghệ thuật bạn đã lưu</p>
        </div>

        @if($likedPhotos->count() > 0)
            <x-wallpaper-grid :wallpapers="$likedPhotos" :likedWallpapers="$likedWallpapers" />
            
            <div class="mt-12 flex justify-center">
                {{ $likedPhotos->links() }}
            </div>
        @else
            <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center shadow-sm max-w-md mx-auto">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 text-3xl">
                    <i class="ph ph-heart-break"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Chưa thích ảnh nào</h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-6">Khám phá và bấm yêu thích các hình nền tuyệt đẹp để lưu trữ tại đây!</p>
                <a href="{{ route('home') }}" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-full inline-flex items-center gap-2 transition duration-200">
                    <i class="ph ph-magnifying-glass"></i>
                    <span>Khám phá ngay</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
