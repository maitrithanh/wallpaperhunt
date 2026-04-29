<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Photos;

class ArtistController extends Controller
{
    public function myProfile()
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }

        $customer = \App\Models\Customer::find(session('customer_id'));
        $partner = Partner::where('email', $customer->email)->first();
        if (!$partner) {
            $partner = Partner::create([
                'full_name' => $customer->full_name,
                'email' => $customer->email,
                'status' => 1,
                'password' => bcrypt(str()->random(16))
            ]);
        }

        return redirect()->route('artist.show', $partner->id);
    }

    public function updateProfile(\Illuminate\Http\Request $request)
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }

        $customer = \App\Models\Customer::find(session('customer_id'));

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|max:4096',
        ]);

        $customer->full_name = $request->full_name;
        $customer->email = $request->email;

        if ($request->filled('password')) {
            $customer->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $customer->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $customer->save();

        // Sync Partner
        $partner = Partner::where('email', $customer->email)->first();
        if ($partner) {
            $partner->full_name = $customer->full_name;
            if ($customer->avatar) {
                $partner->avatar = $customer->avatar;
            }
            $partner->save();
        }

        session()->put('customer_name', $customer->full_name);

        return back()->with('success', 'Cập nhật thông tin tài khoản thành công!');
    }

    public function show($id)
    {
        $artist = Partner::where('status', Partner::STATUS_ACTIVE)
            ->findOrFail($id);

        $isOwner = false;
        $customer = null;
        if (session()->has('customer_id')) {
            $customer = \App\Models\Customer::find(session('customer_id'));
            if ($customer && $customer->email === $artist->email) {
                $isOwner = true;
            }
        }

        $query = Photos::where('partner_id', $artist->id);
        
        if (!$isOwner) {
            $query->where('status', Photos::STATUS_PUBLIC);
        }

        $wallpapers = $query->with('category')
            ->orderByDesc('created_at')
            ->paginate(24);

        $stats = [
            'total_wallpapers' => Photos::where('partner_id', $artist->id)
                ->where('status', Photos::STATUS_PUBLIC)
                ->count(),
            'total_views' => Photos::where('partner_id', $artist->id)
                ->where('status', Photos::STATUS_PUBLIC)
                ->sum('view_count'),
            'total_likes' => Photos::where('partner_id', $artist->id)
                ->where('status', Photos::STATUS_PUBLIC)
                ->sum('like_count'),
        ];
        
        $likedPhotos = [];
        $likedWallpapers = [];
        
        if (session()->has('customer_id')) {
            $likedWallpapers = \DB::table('photo_like')
                ->where('customer_id', session('customer_id'))
                ->pluck('photo_id')
                ->toArray();
                
            if ($isOwner && $customer) {
                $likedPhotos = Photos::whereIn('id', $likedWallpapers)
                    ->where('status', Photos::STATUS_PUBLIC)
                    ->with('category')
                    ->orderByDesc('created_at')
                    ->get();
            }
        }

        return view('artist-profile', compact('artist', 'wallpapers', 'stats', 'isOwner', 'customer', 'likedWallpapers', 'likedPhotos'));
    }

    public function toggleStatus($id)
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }

        $customer = \App\Models\Customer::find(session('customer_id'));
        $wallpaper = Photos::findOrFail($id);

        if (!$wallpaper->partner || $wallpaper->partner->email !== $customer->email) {
            return back()->withErrors(['error' => 'Bạn không có quyền chỉnh sửa ảnh này.']);
        }

        if ($wallpaper->status == Photos::STATUS_PUBLIC) {
            $wallpaper->status = Photos::STATUS_PRIVATE;
        } elseif ($wallpaper->status == Photos::STATUS_PRIVATE) {
            $wallpaper->status = Photos::STATUS_PUBLIC;
        }

        $wallpaper->save();

        return back()->with('success', 'Cập nhật trạng thái hiển thị hình nền thành công!');
    }

    public function likedPhotosPage()
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }

        $customer = \App\Models\Customer::find(session('customer_id'));
        
        $likedIds = \DB::table('photo_like')
            ->where('customer_id', $customer->id)
            ->pluck('photo_id')
            ->toArray();
            
        $likedPhotos = Photos::whereIn('id', $likedIds)
            ->where('status', Photos::STATUS_PUBLIC)
            ->with('category')
            ->orderByDesc('created_at')
            ->paginate(24);

        $likedWallpapers = $likedIds;
        
        return view('liked-wallpapers', compact('likedPhotos', 'likedWallpapers', 'customer'));
    }

    public function uploadedPhotosPage()
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }

        $customer = \App\Models\Customer::find(session('customer_id'));
        $partner = Partner::where('email', $customer->email)->first();
        
        if (!$partner) {
             $uploadedPhotos = collect();
        } else {
             $uploadedPhotos = Photos::where('partner_id', $partner->id)
                 ->with('category')
                 ->orderByDesc('created_at')
                 ->paginate(24);
        }

        $likedWallpapers = [];
        if ($customer) {
            $likedWallpapers = \DB::table('photo_like')
                ->where('customer_id', $customer->id)
                ->pluck('photo_id')
                ->toArray();
        }
        
        return view('uploaded-wallpapers', compact('uploadedPhotos', 'likedWallpapers', 'customer'));
    }

    public function readAllNotifications()
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('login');
        }

        \App\Models\CustomerNotification::where('customer_id', session('customer_id'))
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Đã đánh dấu tất cả thông báo là đã đọc!');
    }
}
