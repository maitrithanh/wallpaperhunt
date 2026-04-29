<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        if (Session::has('customer_id')) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $customer = Customer::where('email', $request->email)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])->withInput();
        }

        if ($customer->status !== Customer::STATUS_ACTIVE) {
            return back()->withErrors(['email' => 'Tài khoản của bạn chưa được kích hoạt.'])->withInput();
        }

        Session::put('customer_id', $customer->id);
        Session::put('customer_name', $customer->full_name);

        return redirect()->intended(route('home'));
    }

    public function showRegister()
    {
        if (Session::has('customer_id')) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6|confirmed',
            'avatar' => 'nullable|image|max:4096',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $customer = Customer::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => Customer::STATUS_ACTIVE,
            'avatar' => $avatarPath,
        ]);

        // Khởi tạo tài khoản Tác giả (Partner) song song
        \App\Models\Partner::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => \App\Models\Partner::STATUS_ACTIVE,
            'avatar' => $avatarPath,
        ]);

        Session::put('customer_id', $customer->id);
        Session::put('customer_name', $customer->full_name);

        return redirect()->route('home');
    }

    public function logout()
    {
        Session::forget(['customer_id', 'customer_name']);
        return redirect()->route('home');
    }
}
