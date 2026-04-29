@extends('layouts.app')

@section('title', 'Ảnh đã tải lên — WallpaperHunt')

@section('content')
<div class="bg-slate-50 min-h-screen text-slate-900 antialiased font-sans relative pt-32 pb-20 px-4 md:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col items-center mb-12">
            <div class="w-16 h-16 bg-blue-50 text-[var(--color-wh-accent)] rounded-full flex items-center justify-center text-2xl shadow-sm mb-4">
                <i class="ph-fill ph-cloud-arrow-up"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-2">Ảnh đã tải lên</h1>
            <p class="text-sm text-slate-500 font-medium">Quản lý danh sách hình nền và trạng thái phê duyệt</p>
        </div>

        @if($uploadedPhotos->count() > 0)
            <div class="bg-white rounded-3xl border border-slate-200/60 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4">Hình ảnh</th>
                                <th class="px-6 py-4">Tên tác phẩm</th>
                                <th class="px-6 py-4">Danh mục</th>
                                <th class="px-6 py-4">Trạng thái</th>
                                <th class="px-6 py-4">Ngày đăng</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
                            @foreach($uploadedPhotos as $photo)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="w-14 h-20 rounded-xl overflow-hidden shadow-sm bg-slate-100 border border-slate-200/40">
                                            <img src="{{ $photo->src ? (str_starts_with($photo->src, 'http') ? $photo->src : asset('storage/' . $photo->src)) : 'https://picsum.photos/seed/' . $photo->id . '/200/300' }}" class="w-full h-full object-cover" alt="{{ $photo->name }}">
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-800">
                                        {{ $photo->name }}
                                    </td>
                                    <td class="px-6 py-4 text-xs font-medium text-slate-500">
                                        {{ $photo->category->name ?? 'Không có' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $statusText = 'Không xác định';
                                            $statusColor = 'bg-slate-100 text-slate-600 border-slate-200';
                                            
                                            if ($photo->status == \App\Models\Photos::STATUS_PUBLIC) {
                                                $statusText = 'Đã duyệt';
                                                $statusColor = 'bg-green-50 text-green-700 border-green-200/60';
                                            } elseif ($photo->status == \App\Models\Photos::STATUS_PENDING) {
                                                $statusText = 'Đang chờ duyệt';
                                                $statusColor = 'bg-amber-50 text-amber-700 border-amber-200/60';
                                            } elseif ($photo->status == \App\Models\Photos::STATUS_DEACTIVATED) {
                                                $statusText = 'Từ chối';
                                                $statusColor = 'bg-red-50 text-red-700 border-red-200/60';
                                            } elseif ($photo->status == \App\Models\Photos::STATUS_PRIVATE) {
                                                $statusText = 'Riêng tư';
                                                $statusColor = 'bg-slate-100 text-slate-600 border-slate-200';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 text-[10px] font-bold rounded-full border {{ $statusColor }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-400">
                                        {{ $photo->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                {{ $uploadedPhotos->links() }}
            </div>
        @else
            <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center shadow-sm max-w-md mx-auto">
                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 text-3xl">
                    <i class="ph ph-image-square"></i>
                </div>
                <h3 class="text-base font-bold text-slate-800 mb-1">Chưa tải ảnh nào</h3>
                <p class="text-xs text-slate-500 leading-relaxed mb-6">Hãy chia sẻ những tác phẩm wallpaper chất lượng của bạn đến cộng đồng!</p>
                <a href="{{ route('upload') }}" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-semibold rounded-full inline-flex items-center gap-2 transition duration-200">
                    <i class="ph ph-upload-simple"></i>
                    <span>Tải lên ngay</span>
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
