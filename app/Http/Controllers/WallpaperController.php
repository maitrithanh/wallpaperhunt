<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Photos;
use Illuminate\Http\Request;

class WallpaperController extends Controller
{
    public function explore(Request $request)
    {
        $query = Photos::where('status', Photos::STATUS_PUBLIC)
            ->with(['category', 'partner']);

        // Search
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });

            // Record search term
            if (strlen($search) >= 2) {
                $termRecord = \App\Models\SearchTerm::firstOrNew(['term' => $search]);
                if ($termRecord->exists) {
                    $termRecord->increment('search_count');
                } else {
                    $termRecord->search_count = 1;
                    $termRecord->save();
                }
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->input('category'));
        }

        // Sort
        $sort = $request->input('sort', 'newest');
        $query = match ($sort) {
            'popular' => $query->orderByDesc('view_count'),
            'most_liked' => $query->orderByDesc('like_count'),
            'oldest' => $query->orderBy('created_at'),
            default => $query->orderByDesc('created_at'),
        };

        $wallpapers = $query->paginate(24);
        $categories = Category::where('status', Category::STATUS_ACTIVE)->get();

        $likedWallpapers = [];
        if (session()->has('customer_id')) {
            $likedWallpapers = \DB::table('photo_like')
                ->where('customer_id', session('customer_id'))
                ->pluck('photo_id')
                ->toArray();
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.wallpaper-grid', compact('wallpapers', 'likedWallpapers'))->render(),
                'hasMore' => $wallpapers->hasMorePages(),
                'nextPage' => $wallpapers->currentPage() + 1,
            ]);
        }

        return view('explore', compact('wallpapers', 'categories', 'likedWallpapers'));
    }

    public function show($id)
    {
        $wallpaper = Photos::where('status', Photos::STATUS_PUBLIC)
            ->with(['category', 'partner', 'album'])
            ->findOrFail($id);

        // Count view by IP address
        $ip = request()->ip();
        $viewExists = \DB::table('photo_view')
            ->where('photo_id', $id)
            ->where('ip_address', $ip)
            ->exists();

        if (!$viewExists) {
            \DB::table('photo_view')->insert([
                'photo_id' => $id,
                'ip_address' => $ip,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $wallpaper->increment('view_count');
        }

        // Related wallpapers
        $related = Photos::where('status', Photos::STATUS_PUBLIC)
            ->where('id', '!=', $wallpaper->id)
            ->where(function ($q) use ($wallpaper) {
                $q->where('category_id', $wallpaper->category_id)
                  ->orWhere('partner_id', $wallpaper->partner_id);
            })
            ->limit(8)
            ->inRandomOrder()
            ->get();

        $likedWallpapers = [];
        if (session()->has('customer_id')) {
            $likedWallpapers = \DB::table('photo_like')
                ->where('customer_id', session('customer_id'))
                ->pluck('photo_id')
                ->toArray();
        }

        return view('wallpaper-detail', compact('wallpaper', 'related', 'likedWallpapers'));
    }

    public function storeComment(\Illuminate\Http\Request $request, $id)
    {
        if (!session()->has('customer_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để bình luận!',
                'redirect' => route('login')
            ], 401);
        }

        $request->validate([
            'content' => 'required|string|max:500',
        ]);

        $customerId = session('customer_id');
        $customer = \App\Models\Customer::find($customerId);

        $comment = \App\Models\PhotoComment::create([
            'photo_id' => $id,
            'customer_id' => $customerId,
            'author_name' => $customer ? $customer->full_name : 'Người dùng',
            'content' => $request->content,
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'author' => $comment->author_name,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }

    public function like($id)
    {
        if (!session()->has('customer_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để thả tim!',
                'redirect' => route('login')
            ], 401);
        }

        $customerId = session('customer_id');
        $wallpaper = Photos::findOrFail($id);

        $existing = \DB::table('photo_like')
            ->where('photo_id', $id)
            ->where('customer_id', $customerId)
            ->first();

        if ($existing) {
            // Unlike
            \DB::table('photo_like')->where('id', $existing->id)->delete();
            if ($wallpaper->like_count > 0) {
                $wallpaper->decrement('like_count');
            }
            $liked = false;
        } else {
            // Like
            \DB::table('photo_like')->insert([
                'photo_id' => $id,
                'customer_id' => $customerId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $wallpaper->increment('like_count');
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes' => $wallpaper->fresh()->like_count,
        ]);
    }

    public function download($id)
    {
        $wallpaper = Photos::where('status', Photos::STATUS_PUBLIC)->findOrFail($id);

        $path = storage_path('app/public/' . $wallpaper->src);
        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path, $wallpaper->name . '.' . pathinfo($wallpaper->src, PATHINFO_EXTENSION));
    }

    public function uploadForm()
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để tải lên hình nền.');
        }
        $categories = \App\Models\Category::whereIn('status', [
            \App\Models\Category::STATUS_ACTIVE, 
            \App\Models\Category::STATUS_PENDING
        ])->get();
        return view('upload', compact('categories'));
    }

    public function upload(Request $request)
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }


        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:43008', // Max 42MB
        ]);

        $customerId = session('customer_id');
        $customer = \App\Models\Customer::find($customerId);

        // Find or create Partner for this Customer
        $partner = \App\Models\Partner::firstOrCreate(
            ['email' => $customer->email],
            [
                'full_name' => $customer->full_name,
                'status' => 1,
                'password' => bcrypt(str()->random(16))
            ]
        );

        // Find or create Default Album for this Partner
        $album = \App\Models\Albums::firstOrCreate(
            ['partner_id' => $partner->id],
            [
                'name' => 'Album mặc định',
                'description' => 'Album chứa ảnh tải lên của người dùng',
                'status' => 1
            ]
        );

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('photos', $filename, 'public');

            \App\Models\Photos::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'src' => 'photos/' . $filename,
                'status' => \App\Models\Photos::STATUS_PENDING,
                'album_id' => $album->id,
                'partner_id' => $partner->id,
            ]);

            // Notify Admin via Filament database notifications
            try {
                \Filament\Notifications\Notification::make()
                    ->title('Có hình nền mới cần duyệt')
                    ->body("Người dùng {$customer->full_name} đã tải lên tác phẩm '{$request->name}'.")
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->sendToDatabase(\App\Models\User::all());
            } catch (\Exception $e) {
                // Fallback gracefully
            }

            return redirect()->route('home')->with('success', 'Ảnh đã được tải lên và đang chờ Admin duyệt!');
        }

        return back()->with('error', 'Có lỗi xảy ra khi tải ảnh lên.');
    }

    public function moderationIndex()
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }

        $pendingWallpapers = \App\Models\Photos::where('status', \App\Models\Photos::STATUS_PENDING)
            ->with(['category', 'partner'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin-moderate', compact('pendingWallpapers'));
    }

    public function approve($id)
    {
        $wallpaper = \App\Models\Photos::findOrFail($id);
        
        $category = $wallpaper->category;
        if ($category && $category->status !== \App\Models\Category::STATUS_ACTIVE) {
            return back()->with('error', 'Danh mục "' . $category->name . '" chưa được duyệt. Hãy duyệt danh mục trước!');
        }

        $wallpaper->status = \App\Models\Photos::STATUS_PUBLIC;
        $wallpaper->save();

        // Send Notification
        $partner = $wallpaper->partner;
        if ($partner) {
            $customer = \App\Models\Customer::where('email', $partner->email)->first();
            if ($customer) {
                \App\Models\CustomerNotification::create([
                    'customer_id' => $customer->id,
                    'title' => 'Hình nền đã được duyệt! 🎉',
                    'message' => 'Chúc mừng! Tác phẩm "' . $wallpaper->name . '" của bạn đã chính thức được phê duyệt và công khai.',
                    'type' => 'approval'
                ]);
            }
        }

        return back()->with('success', 'Đã duyệt hình nền thành công!');
    }

    public function reject($id)
    {
        $wallpaper = \App\Models\Photos::findOrFail($id);
        $wallpaper->status = \App\Models\Photos::STATUS_DEACTIVATED;
        $wallpaper->save();

        // Send Notification
        $partner = $wallpaper->partner;
        if ($partner) {
            $customer = \App\Models\Customer::where('email', $partner->email)->first();
            if ($customer) {
                \App\Models\CustomerNotification::create([
                    'customer_id' => $customer->id,
                    'title' => 'Hình nền bị từ chối ⚠️',
                    'message' => 'Rất tiếc, hình nền "' . $wallpaper->name . '" chưa đáp ứng đủ điều kiện kiểm duyệt của cộng đồng.',
                    'type' => 'rejection'
                ]);
            }
        }

        return back()->with('success', 'Đã từ chối hình nền!');
    }

    public function feed(Request $request)
    {
        $query = \App\Models\Photos::where('status', \App\Models\Photos::STATUS_PUBLIC)
            ->with(['category', 'partner']);

        // Generate or get a seed from session for consistent randomization across pages
        if (!$request->has('page') || !session()->has('feed_seed')) {
            session(['feed_seed' => rand(1, 100000)]);
        }
        $seed = session('feed_seed');

        $wallpapers = $query->orderByRaw('RAND(?)', [$seed])->paginate(10);

        $likedWallpapers = [];
        if (session()->has('customer_id')) {
            $likedWallpapers = \DB::table('photo_like')
                ->where('customer_id', session('customer_id'))
                ->pluck('photo_id')
                ->toArray();
        }

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.feed-items', compact('wallpapers', 'likedWallpapers'))->render(),
                'hasMore' => $wallpapers->hasMorePages(),
                'nextPage' => $wallpapers->currentPage() + 1,
            ]);
        }

        return view('feed', compact('wallpapers', 'likedWallpapers'));
    }
}
