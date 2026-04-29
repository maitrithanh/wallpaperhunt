<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Photos;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', Category::STATUS_ACTIVE)
            ->withCount(['photos' => function ($query) {
                $query->where('status', Photos::STATUS_PUBLIC);
            }])
            ->get();

        return view('categories', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', Category::STATUS_ACTIVE)
            ->firstOrFail();

        $wallpapers = Photos::where('status', Photos::STATUS_PUBLIC)
            ->where('category_id', $category->id)
            ->with('partner')
            ->orderByDesc('created_at')
            ->paginate(24);

        return view('category-detail', compact('category', 'wallpapers'));
    }
    public function quickCreate(\Illuminate\Http\Request $request)
    {
        if (!session()->has('customer_id')) {
            return response()->json(['success' => false, 'message' => 'Bạn cần đăng nhập.'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'required|image|max:4096',
        ]);

        $slug = \Str::slug($request->name);
        
        if (Category::where('slug', $slug)->exists()) {
            return response()->json(['success' => false, 'message' => 'Danh mục này đã tồn tại hoặc đang chờ duyệt.'], 400);
        }

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => $slug,
            'avatar' => $avatarPath,
            'status' => Category::STATUS_PENDING,
            'description' => 'Người dùng đề xuất'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đã gửi đề xuất danh mục thành công!',
            'category' => $category
        ]);
    }
}
