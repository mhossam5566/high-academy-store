<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    use ImageTrait;

    public function index()
    {
        $statistics = $this->getStatistics();
        return view('dashboard.pages.home.dashboard', compact('statistics'));
    }

    private function getStatistics()
    {
        // Orders Statistics
        $totalOrders = \App\Models\Order::count();
        $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
        $deliveredOrders = \App\Models\Order::where('status', 'delivered')->count();
        $cancelledOrders = \App\Models\Order::where('status', 'cancelled')->count();
        $paidOrders = \App\Models\Order::where('is_paid', 1)->count();
        $unpaidOrders = \App\Models\Order::where('is_paid', 0)->count();

        // Revenue
        $totalRevenue = \App\Models\Order::where('is_paid', 1)->sum('total');
        $todayRevenue = \App\Models\Order::where('is_paid', 1)
            ->whereDate('created_at', today())->sum('total');
        $monthRevenue = \App\Models\Order::where('is_paid', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        // Voucher Orders Statistics
        $totalVoucherOrders = \App\Models\VouchersOrder::count();
        $pendingVoucherOrders = \App\Models\VouchersOrder::where('state', 'pending')->count();
        $successVoucherOrders = \App\Models\VouchersOrder::where('state', 'success')->count();
        $completedVoucherOrders = \App\Models\VouchersOrder::where('state', 'completed')->count();

        // Recent Orders
        $recentOrders = \App\Models\Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Recent Voucher Orders
        $recentVoucherOrders = \App\Models\VouchersOrder::latest()
            ->take(5)
            ->get();

        return [
            'orders' => [
                'total' => $totalOrders,
                'pending' => $pendingOrders,
                'delivered' => $deliveredOrders,
                'cancelled' => $cancelledOrders,
                'paid' => $paidOrders,
                'unpaid' => $unpaidOrders,
                'recent' => $recentOrders,
            ],
            'revenue' => [
                'total' => $totalRevenue,
                'today' => $todayRevenue,
                'month' => $monthRevenue,
            ],
            'vouchers' => [
                'total' => $totalVoucherOrders,
                'pending' => $pendingVoucherOrders,
                'success' => $successVoucherOrders,
                'completed' => $completedVoucherOrders,
                'recent' => $recentVoucherOrders,
            ]
        ];
    }

    public function register()
    {
        return view('admin.auth.register');
    }

    public function signup(AdminRequest $request)
    {
        $data = $request->only('name', 'email');
        if ($request->hasFile('photo')) {
            $image_name = $this->ImageNamePath($request->file('photo'), 'public/images/admins');
            $data['photo'] = $image_name;
        }
        $data['password'] = bcrypt($request->password);
        $admins = Admin::create($data);
        auth()->guard('admin')->login($admins);
        if ($admins) {
            toastr()->success('Data has been saved successfully!');
            return redirect(route('dashboard.index'));
        }
    }

    public function login()
    {
        return view('dashboard.pages.auth.login');
    }

    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (auth()->guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json(['message' => 'Login successful', 'redirect' => route('dashboard.index')], 200);
        } else {
            return response()->json(['message' => 'Email or password are wrong.'], 400);
        }
    }

    function adminLogout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('login');
    }

    public function profile()
    {
        return view('admin.auth.profile');
    }

    public function AccountUpdate(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:admins,email,' . Auth::user()->id,
            'photo' => 'nullable|mimes:jpg,png'
        ]);
        $admin = Admin::FindOrFail(Auth::user()->id);
        if ($request->photo) {
            Storage::delete('public/images/admins/' . $admin->photo);
            $image_name = $this->ImageNamePath($request->file('photo'), 'public/images/admins');
            $data['photo'] = $image_name;
        }
        $admin->update($data);
        toastr()->success('Data has been updated successfully!');
        return redirect()->route('dashboard.profile');
    }

    public function changePass(Request $request)
    {
        $request->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        $current_admin = auth()->user();
        if (Hash::check($request->old_password, $current_admin->password)) {
            $current_admin->update([
                'password' => bcrypt($request->new_password)
            ]);
            toastr()->success('Password has been Updated successfully!');
            return redirect()->route('dashboard.profile');
        } else {
            toastr()->error('old password does not matched');
            return redirect()->back();
        }
    }
}
