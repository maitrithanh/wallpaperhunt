@extends('layouts.app')

@push('head')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <style>
        .ts-wrapper .ts-control {
            border-radius: 12px !important;
            padding: 0.75rem 1.25rem !important;
            border-color: var(--color-wh-border) !important;
            background-color: var(--color-wh-surface) !important;
            font-size: 0.875rem !important;
            color: var(--color-wh-text) !important;
            transition: all 0.3s ease;
        }
        .ts-wrapper.focus .ts-control {
            border-color: var(--color-wh-accent) !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
        }
        .ts-dropdown {
            border-radius: 12px !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
            border: 1px solid var(--color-wh-border) !important;
            background-color: var(--color-wh-surface) !important;
            margin-top: 0.5rem !important;
            z-index: 50 !important;
            overflow: hidden;
            padding: 0.5rem !important;
        }
        .ts-dropdown .option {
            padding: 0.75rem 1rem !important;
            border-radius: 8px;
            font-size: 0.875rem !important;
            color: var(--color-wh-text) !important;
            transition: all 0.2s ease;
        }
        .ts-dropdown .active {
            background-color: var(--color-wh-accent) !important;
            color: #ffffff !important;
        }
        
        /* Custom styles for Drag & Drop */
        .drag-over {
            border-color: var(--color-wh-accent) !important;
            background-color: rgba(37, 99, 235, 0.04) !important;
            transform: scale(0.99);
        }
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-[var(--color-wh-dark)] pt-28 pb-16 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-5xl mx-auto">
        
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 mb-6 text-[var(--color-wh-text-muted)] text-xs font-semibold">
            <a href="{{ route('home') }}" class="hover:text-[var(--color-wh-accent)] transition">Trang chủ</a>
            <i class="ph ph-caret-right text-[10px]"></i>
            <span class="text-[var(--color-wh-text)]">Tải lên</span>
        </div>

        <div class="bg-[var(--color-wh-surface)] rounded-3xl border border-[var(--color-wh-border)] p-6 md:p-10 relative overflow-hidden shadow-sm">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-8 mb-8 border-b border-[var(--color-wh-border)]">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-[var(--color-wh-text)] tracking-tight flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-[var(--color-wh-accent)] flex items-center justify-center text-white shadow-lg shadow-blue-600/30">
                            <i class="ph ph-cloud-arrow-up text-xl"></i>
                        </span>
                        Tải Lên Hình Nền
                    </h1>
                    <p class="text-sm text-[var(--color-wh-text-muted)] mt-2">Đóng góp những tác phẩm chất lượng cao cho cộng đồng WallpaperHunter.</p>
                </div>
                
                <div class="flex items-center gap-4 bg-blue-50/50 border border-blue-100/50 px-4 py-2.5 rounded-2xl text-[var(--color-wh-accent)] text-xs font-semibold">
                    <i class="ph ph-info text-base"></i>
                    <span>Tác phẩm sẽ được kiểm duyệt trước khi hiển thị.</span>
                </div>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-2xl text-sm font-medium flex items-center gap-3">
                    <i class="ph-fill ph-warning-circle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form id="main-upload-form" action="{{ route('upload.submit') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-12 gap-8" onsubmit="return validateMainForm(event)">
                @csrf

                {{-- Left Side: Image Upload --}}
                <div class="md:col-span-7 flex flex-col">
                    <label class="block text-sm font-bold text-[var(--color-wh-text)] mb-3">Hình nền tải lên</label>
                    
                    <div class="mt-1 relative group flex-1 min-h-[350px] md:min-h-0">
                        {{-- Upload Trigger Box --}}
                        <label id="upload-drop-zone" for="image" class="flex flex-col items-center justify-center px-6 h-full border-2 border-[var(--color-wh-border)] border-dashed rounded-2xl hover:border-[var(--color-wh-accent)] hover:bg-slate-50/50 transition-all duration-300 cursor-pointer relative">
                            
                            <div class="w-16 h-16 bg-[var(--color-wh-surface-2)] rounded-2xl flex items-center justify-center shadow-sm mb-6 text-[var(--color-wh-text-muted)] group-hover:text-[var(--color-wh-accent)] group-hover:scale-110 transition duration-300">
                                <i class="ph ph-image-square text-3xl"></i>
                            </div>
                            
                            <div class="text-center">
                                <span class="text-base font-bold text-[var(--color-wh-text)] block mb-1">Kéo và thả tệp tại đây</span>
                                <span class="text-xs text-[var(--color-wh-text-muted)]">hoặc nhấn để <span class="text-[var(--color-wh-accent)] font-bold hover:underline">chọn ảnh</span> từ máy tính</span>
                            </div>
                            
                            <div class="mt-8 flex gap-3 text-[10px] font-semibold text-[var(--color-wh-text-muted)] bg-[var(--color-wh-surface-2)] border border-[var(--color-wh-border)] px-4 py-2 rounded-xl">
                                <span>PNG, JPG, WEBP</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full my-auto"></span>
                                <span>Tối đa 42MB</span>
                            </div>
                            
                            <input id="image" name="image" type="file" class="sr-only" accept="image/*" required onchange="previewImage(event)">
                        </label>

                        {{-- Image Preview Zone --}}
                        <div id="image-preview-container" class="hidden absolute inset-0 bg-slate-950 rounded-2xl overflow-hidden shadow-inner group/preview">
                            <img id="image-preview" src="#" alt="Preview" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-slate-950/50 opacity-0 group-hover/preview:opacity-100 transition-opacity duration-300 flex flex-col items-center justify-center gap-3 backdrop-blur-sm">
                                <label for="image" class="btn btn-glass text-xs font-bold transition duration-200 flex items-center gap-2 cursor-pointer">
                                    <i class="ph ph-pencil-simple text-sm"></i> Thay đổi ảnh
                                </label>
                                <button type="button" onclick="removeImage()" class="btn bg-red-600 hover:bg-red-700 text-white text-xs font-bold transition duration-200 flex items-center gap-2">
                                    <i class="ph ph-trash text-sm"></i> Xóa ảnh
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-2 text-xs font-semibold text-red-600 flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- Right Side: Form Fields --}}
                <div class="md:col-span-5 flex flex-col justify-between">
                    <div class="space-y-6">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-bold text-[var(--color-wh-text)] mb-2">Tiêu đề hình nền</label>
                            <input type="text" name="name" id="name" required
                                class="w-full px-4 py-3 rounded-xl border border-[var(--color-wh-border)] focus:outline-none focus:border-[var(--color-wh-accent)] focus:ring-1 focus:ring-[var(--color-wh-accent)] text-[var(--color-wh-text)] placeholder-[var(--color-wh-text-muted)] transition-all font-medium text-sm"
                                placeholder="VD: Hoàng hôn trên biển"
                                value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-bold text-[var(--color-wh-text)] mb-2">Mô tả (Tùy chọn)</label>
                            <textarea name="description" id="description" rows="4"
                                class="w-full px-4 py-3 rounded-xl border border-[var(--color-wh-border)] focus:outline-none focus:border-[var(--color-wh-accent)] focus:ring-1 focus:ring-[var(--color-wh-accent)] text-[var(--color-wh-text)] placeholder-[var(--color-wh-text-muted)] transition-all font-medium text-sm resize-none"
                                placeholder="Mô tả câu chuyện, thông số máy ảnh hoặc cảm hứng sáng tác...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="category_id" class="block text-sm font-bold text-[var(--color-wh-text)]">Danh mục</label>
                                <button type="button" onclick="openCategoryModal()" class="text-xs font-bold text-[var(--color-wh-accent)] hover:underline flex items-center gap-1">
                                    <i class="ph ph-plus-circle"></i> Đề xuất mới
                                </button>
                            </div>
                            <select name="category_id" id="category_id" required class="w-full">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} data-avatar="{{ $category->avatar ? (str_starts_with($category->avatar, 'http') ? $category->avatar : asset('storage/' . $category->avatar)) : 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=100&auto=format&fit=crop' }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1.5 text-xs font-semibold text-red-600 flex items-center gap-1"><i class="ph-fill ph-warning-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="pt-8 border-t border-[var(--color-wh-border)] mt-8">
                        <x-button type="submit" variant="primary" class="w-full text-sm py-3 flex items-center justify-center gap-2">
                            <i class="ph ph-check-square-offset text-base"></i>
                            <span>Tải Lên Hệ Thống</span>
                        </x-button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Quick Create Category Modal --}}
    <div id="create-category-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-all duration-300" onclick="closeCategoryModal()"></div>
        
        <div class="relative bg-[var(--color-wh-surface)] rounded-2xl w-full max-w-md p-6 shadow-xl border border-[var(--color-wh-border)] transform scale-95 opacity-0 transition-all duration-300 ease-out modal-content">
            
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-[var(--color-wh-text)] flex items-center gap-2">
                    <i class="ph ph-folder-plus text-[var(--color-wh-accent)] text-xl"></i>
                    Đề xuất danh mục
                </h3>
                <button type="button" onclick="closeCategoryModal()" class="w-8 h-8 rounded-xl text-[var(--color-wh-text-muted)] hover:bg-[var(--color-wh-surface-2)] transition flex items-center justify-center"><i class="ph ph-x text-base"></i></button>
            </div>

            <div id="category-error" class="hidden mb-4 p-3 bg-red-50 border border-red-100 text-red-600 rounded-xl text-xs font-semibold flex items-center gap-2">
                <i class="ph-fill ph-warning-circle text-sm"></i>
                <span id="category-error-text">Có lỗi xảy ra.</span>
            </div>

            <form id="quick-category-form" onsubmit="submitCategoryForm(event)" class="space-y-5 text-left">
                <div>
                    <label for="new_category_name" class="block text-xs font-bold text-[var(--color-wh-text)] mb-2">Tên danh mục</label>
                    <input type="text" id="new_category_name" required 
                        class="w-full px-4 py-3 rounded-xl border border-[var(--color-wh-border)] focus:outline-none focus:border-[var(--color-wh-accent)] focus:ring-1 focus:ring-[var(--color-wh-accent)] text-[var(--color-wh-text)] placeholder-[var(--color-wh-text-muted)] transition-all font-medium text-sm" 
                        placeholder="VD: Tối giản, Cyberpunk">
                </div>

                <div>
                    <label for="new_category_avatar" class="block text-xs font-bold text-[var(--color-wh-text)] mb-2">Ảnh đại diện (Tối đa 4MB)</label>
                    <input type="file" id="new_category_avatar" required accept="image/*" 
                        class="w-full text-xs text-[var(--color-wh-text-muted)] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[var(--color-wh-surface-2)] file:text-[var(--color-wh-text)] hover:file:opacity-80 cursor-pointer transition">
                </div>

                <div class="pt-3 flex gap-3">
                    <x-button type="button" variant="outline" onclick="closeCategoryModal()" class="w-1/2 text-xs font-bold">Hủy bỏ</x-button>
                    <x-button type="submit" id="category-submit-btn" variant="primary" class="w-1/2 text-xs font-bold flex items-center justify-center gap-2">
                        <span id="category-btn-text">Gửi đề xuất</span>
                        <div id="category-loading" class="hidden w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    </x-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Confirm Upload Modal --}}
    <div id="confirm-upload-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/60 backdrop-blur-sm transition-all duration-300" onclick="closeConfirmModal()"></div>
        
        <div class="relative bg-[var(--color-wh-surface)] rounded-2xl w-full max-w-md p-6 shadow-xl border border-[var(--color-wh-border)] transform scale-95 opacity-0 transition-all duration-300 ease-out confirm-modal-content">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-[var(--color-wh-text)] flex items-center gap-2">
                    <i class="ph-fill ph-shield-check text-green-600 text-xl"></i>
                    Xác nhận tải lên
                </h3>
                <button type="button" onclick="closeConfirmModal()" class="w-8 h-8 rounded-xl text-[var(--color-wh-text-muted)] hover:bg-[var(--color-wh-surface-2)] transition flex items-center justify-center"><i class="ph ph-x text-base"></i></button>
            </div>
            
            <p class="text-xs text-[var(--color-wh-text-muted)] mb-6">Vui lòng kiểm tra lại thông tin tác phẩm trước khi đăng tải.</p>

            {{-- Preview Box --}}
            <div class="bg-[var(--color-wh-surface-2)] border border-[var(--color-wh-border)] rounded-xl p-4 mb-6 flex items-center gap-4">
                <img id="confirm-preview-img" src="#" class="w-16 h-24 object-cover rounded-lg shadow-sm bg-slate-200 border border-white" alt="">
                <div class="flex-1 overflow-hidden">
                    <h4 id="confirm-title" class="text-sm font-bold text-[var(--color-wh-text)] truncate">-- Không tên --</h4>
                    <p id="confirm-category" class="text-xs font-semibold text-[var(--color-wh-accent)] mt-1 bg-blue-50/50 px-2.5 py-1 rounded inline-block">-- Chưa chọn danh mục --</p>
                </div>
            </div>

            <div class="flex gap-3">
                <x-button type="button" variant="outline" onclick="closeConfirmModal()" class="w-1/2 text-xs font-bold">Hủy bỏ</x-button>
                <x-button type="button" id="confirm-upload-btn" onclick="submitMainForm()" variant="primary" class="w-1/2 text-xs font-bold flex items-center justify-center gap-2">
                    Xác nhận tải
                </x-button>
            </div>
        </div>
    </div>
</div>

<script>
    function showToast(message, type = 'error') {
        if (window.showToast) {
            window.showToast(message, type);
        }
    }

    let tomSelectInstance;

    document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    showToast("{{ $error }}", 'error');
                @endforeach
            @endif
        }, 200);

        if (document.getElementById("category_id")) {
            tomSelectInstance = new TomSelect("#category_id", {
                create: false,
                sortField: {
                    field: "text",
                    direction: "asc"
                },
                placeholder: "Tìm kiếm danh mục...",
                maxOptions: 50,
                render: {
                    no_results: function(data, escape) {
                        return '<div class="no-results p-3 text-center">' +
                               '<p class="text-xs text-slate-500 mb-2">Không tìm thấy danh mục này</p>' +
                               '<button type="button" onclick="openCategoryModal()" class="btn btn-primary btn-sm mx-auto flex items-center gap-1">' +
                               '<i class="ph ph-plus-circle"></i> Đề xuất thêm mới' +
                               '</button>' +
                               '</div>';
                    },
                    option: function(data, escape) {
                        let avatar = 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=100&auto=format&fit=crop';
                        if (data.$option && data.$option.getAttribute('data-avatar')) {
                            avatar = data.$option.getAttribute('data-avatar');
                        } else if (data.avatar) {
                            avatar = data.avatar;
                        }
                        return '<div class="flex items-center gap-3 p-1">' +
                               '<img src="' + avatar + '" class="w-8 h-8 rounded-lg object-cover shadow-sm border border-slate-100">' +
                               '<span class="text-xs font-bold text-slate-700">' + escape(data.text) + '</span>' +
                               '</div>';
                    },
                    item: function(data, escape) {
                        let avatar = 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=100&auto=format&fit=crop';
                        if (data.$option && data.$option.getAttribute('data-avatar')) {
                            avatar = data.$option.getAttribute('data-avatar');
                        } else if (data.avatar) {
                            avatar = data.avatar;
                        }
                        return '<div class="flex items-center gap-2">' +
                               '<img src="' + avatar + '" class="w-6 h-6 rounded-md object-cover shadow-sm border border-slate-100">' +
                               '<span class="text-xs font-bold text-slate-800">' + escape(data.text) + '</span>' +
                               '</div>';
                    }
                }
            });
        }
    });

    function openCategoryModal() {
        const modal = document.getElementById('create-category-modal');
        const content = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCategoryModal() {
        const modal = document.getElementById('create-category-modal');
        const content = modal.querySelector('.modal-content');
        
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('category-error').classList.add('hidden');
        }, 200);
    }

    async function submitCategoryForm(event) {
        event.preventDefault();
        const btnText = document.getElementById('category-btn-text');
        const loading = document.getElementById('category-loading');
        const errorDiv = document.getElementById('category-error');
        const submitBtn = document.getElementById('category-submit-btn');

        btnText.innerText = 'Đang xử lý...';
        loading.classList.remove('hidden');
        submitBtn.disabled = true;
        errorDiv.classList.add('hidden');

        const formData = new FormData();
        formData.append('name', document.getElementById('new_category_name').value);
        
        const avatarInput = document.getElementById('new_category_avatar');
        if (avatarInput.files && avatarInput.files[0]) {
            const file = avatarInput.files[0];
            if (!isValidImage(file)) {
                errorDiv.querySelector('#category-error-text').innerText = 'Tệp đại diện không hợp lệ.';
                errorDiv.classList.remove('hidden');
                btnText.innerText = 'Gửi đề xuất';
                loading.classList.add('hidden');
                submitBtn.disabled = false;
                return;
            }
            formData.append('avatar', file);
        }

        try {
            const response = await fetch("{{ route('categories.quick-create') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                if (tomSelectInstance) {
                    tomSelectInstance.addOption({
                        value: data.category.id,
                        text: data.category.name + ' (Chờ duyệt)',
                        avatar: data.category.avatar ? (data.category.avatar.startsWith('http') ? data.category.avatar : '/storage/' + data.category.avatar) : 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=100&auto=format&fit=crop'
                    });
                    tomSelectInstance.setValue(data.category.id);
                }
                closeCategoryModal();
                showToast('Đã gửi đề xuất danh mục "' + data.category.name + '" thành công!', 'success');
            } else {
                errorDiv.querySelector('#category-error-text').innerText = data.message || 'Có lỗi xảy ra.';
                errorDiv.classList.remove('hidden');
            }
        } catch (error) {
            errorDiv.querySelector('#category-error-text').innerText = 'Lỗi kết nối máy chủ.';
            errorDiv.classList.remove('hidden');
        } finally {
            btnText.innerText = 'Gửi đề xuất';
            loading.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    let isConfirmed = false;

    function isValidImage(file) {
        if (!file) return false;

        // 1. Validate File Size (Max 42MB)
        const maxSizeBytes = 42 * 1024 * 1024;
        if (file.size > maxSizeBytes) {
            showToast('File quá lớn. Dung lượng tối đa là 42MB.', 'error');
            return false;
        }

        // 2. Validate Extension (PNG, JPG, WEBP)
        const allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
        let extension = '';
        if (file.name && file.name.includes('.')) {
            extension = file.name.split('.').pop().toLowerCase();
        } else if (file.type) {
            extension = file.type.split('/').pop().toLowerCase();
        }

        if (!allowedExtensions.includes(extension)) {
            showToast('Định dạng ảnh không hợp lệ (Chỉ nhận PNG, JPG, WEBP).', 'error');
            return false;
        }

        return true;
    }

    function validateMainForm(e) {
        if (isConfirmed) return true;
        
        if (e) e.preventDefault();

        const input = document.getElementById('image');
        const titleInput = document.getElementById('name');

        if (!input.files || !input.files[0]) {
            showToast('Vui lòng chọn hình nền để tải lên.', 'error');
            return false;
        }

        const file = input.files[0];
        if (!isValidImage(file)) {
            return false;
        }

        // Populating confirm data
        document.getElementById('confirm-title').innerText = titleInput.value || '-- Không tên --';
        
        // Fetch selected category text
        let catText = '-- Chưa chọn danh mục --';
        if (tomSelectInstance) {
            const val = tomSelectInstance.getValue();
            if (val) {
                const opt = tomSelectInstance.options[val];
                if (opt) catText = opt.text;
            }
        }
        document.getElementById('confirm-category').innerText = catText;

        // Render confirm preview image
        const confirmImg = document.getElementById('confirm-preview-img');
        const reader = new FileReader();
        reader.onload = function(evt) {
            confirmImg.src = evt.target.result;
        }
        reader.readAsDataURL(file);

        // Open Modal
        const modal = document.getElementById('confirm-upload-modal');
        const content = modal.querySelector('.confirm-modal-content');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);

        return false;
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirm-upload-modal');
        const content = modal.querySelector('.confirm-modal-content');
        
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    function submitMainForm() {
        const input = document.getElementById('image');
        if (!input.files || !input.files[0]) {
            alert('Vui lòng chọn hình nền để tải lên.');
            return;
        }
        
        if (!isValidImage(input.files[0])) {
            return;
        }

        // Add loading state
        const btn = document.getElementById('confirm-upload-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang tải lên...
            `;
        }

        isConfirmed = true;
        document.getElementById('main-upload-form').submit();
    }

    function previewImage(event) {
        const input = event.target;
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');

        if (input.files && input.files[0]) {
            const file = input.files[0];

            // Validate Frontend
            if (!isValidImage(file)) {
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        const input = document.getElementById('image');
        const container = document.getElementById('image-preview-container');
        const preview = document.getElementById('image-preview');
        
        input.value = ""; 
        preview.src = "#";
        container.classList.add('hidden');
    }

    // Drag and Drop Enhancements
    const dropZone = document.getElementById('upload-drop-zone');
    const imageInput = document.getElementById('image');

    if (dropZone && imageInput) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
            }, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('drag-over');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('drag-over');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            if (files && files[0]) {
                if (!isValidImage(files[0])) {
                    return;
                }
                imageInput.files = files;
                const event = { target: imageInput };
                previewImage(event);
            }
        }, false);
    }
</script>
@endsection
