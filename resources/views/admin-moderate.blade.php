@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-slate-900 mb-2">Phê Duyệt Hình Nền</h1>
            <p class="text-slate-500">Danh sách hình nền đang chờ Admin duyệt trước khi hiển thị công khai.</p>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($pendingWallpapers->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($pendingWallpapers as $wallpaper)
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 overflow-hidden border border-slate-100 flex flex-col h-full">
                        {{-- Image Preview --}}
                        <div class="relative aspect-[16/10] bg-slate-900 flex items-center justify-center">
                            <img src="{{ asset('storage/' . $wallpaper->src) }}" alt="{{ $wallpaper->name }}" class="w-full h-full object-cover">
                            <span class="absolute top-4 left-4 px-3 py-1 bg-amber-500 text-white text-xs font-semibold rounded-full shadow-sm">Chờ duyệt</span>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $wallpaper->name }}</h3>
                                @if($wallpaper->description)
                                    <p class="text-slate-500 text-sm mb-4">{{ $wallpaper->description }}</p>
                                @endif
                                <div class="flex items-center gap-4 text-sm text-slate-600 mb-4">
                                    <div class="flex items-center gap-1">
                                        <i class="ph ph-user"></i>
                                        <span>{{ $wallpaper->partner->full_name ?? 'Ẩn danh' }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <i class="ph ph-squares-four"></i>
                                        <span>{{ $wallpaper->category->name ?? 'Không có' }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-4 mt-4">
                                <form action="{{ route('admin.approve', $wallpaper->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl shadow-md shadow-green-500/20 transition-all duration-300 flex items-center justify-center gap-2">
                                        <i class="ph ph-check"></i> Duyệt
                                    </button>
                                </form>
                                <form action="{{ route('admin.reject', $wallpaper->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full py-2.5 px-4 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl shadow-md shadow-red-500/20 transition-all duration-300 flex items-center justify-center gap-2">
                                        <i class="ph ph-x"></i> Từ chối
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 max-w-md mx-auto">
                <i class="ph ph-image-broken text-5xl text-slate-300 mb-4"></i>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Không có hình nền nào cần duyệt</h3>
                <p class="text-slate-500 text-sm">Tất cả hình nền đã được xử lý xong!</p>
            </div>
        @endif
    </div>
</div>
@endsection
