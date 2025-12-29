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

    public function index(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', now()->endOfDay()->format('Y-m-d'));
        
        $statistics = $this->getStatistics($dateFrom, $dateTo);
        return view('dashboard.pages.home.dashboard', compact('statistics', 'dateFrom', 'dateTo'));
    }

    private function getStatistics($dateFrom, $dateTo)
    {
        // Orders Statistics (filtered by date range)
        $ordersQuery = \App\Models\Order::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);
        $totalOrders = (clone $ordersQuery)->count();
        $pendingOrders = (clone $ordersQuery)->where('status', 'pending')->count();
        $deliveredOrders = (clone $ordersQuery)->where('status', 'delivered')->count();
        $cancelledOrders = (clone $ordersQuery)->where('status', 'cancelled')->count();
        $paidOrders = (clone $ordersQuery)->where('is_paid', 1)->count();
        $unpaidOrders = (clone $ordersQuery)->where('is_paid', 0)->count();

        // Revenue (filtered by date range)
        $totalRevenue = (clone $ordersQuery)->where('is_paid', 1)->sum('total');
        $todayRevenue = \App\Models\Order::where('is_paid', 1)
            ->whereDate('created_at', today())->sum('total');
        $monthRevenue = \App\Models\Order::where('is_paid', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');
        $weekRevenue = \App\Models\Order::where('is_paid', 1)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total');

        // Average Order Value
        $avgOrderValue = $paidOrders > 0 ? $totalRevenue / $paidOrders : 0;

        // Voucher Orders Statistics (filtered by date range)
        $vouchersQuery = \App\Models\VouchersOrder::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59']);
        $totalVoucherOrders = (clone $vouchersQuery)->count();
        $pendingVoucherOrders = (clone $vouchersQuery)->where('state', 'pending')->count();
        $successVoucherOrders = (clone $vouchersQuery)->where('state', 'success')->count();
        $completedVoucherOrders = (clone $vouchersQuery)->where('state', 'completed')->count();

        // Products & Users Statistics
        $totalProducts = \App\Models\Product::count();
        $activeProducts = \App\Models\Product::where('is_deleted', 0)->count();
        $totalUsers = \App\Models\User::count();
        $newUsersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Growth Rate (comparing with previous period)
        $periodDays = \Carbon\Carbon::parse($dateFrom)->diffInDays(\Carbon\Carbon::parse($dateTo));
        $previousDateFrom = \Carbon\Carbon::parse($dateFrom)->subDays($periodDays)->format('Y-m-d');
        $previousDateTo = \Carbon\Carbon::parse($dateFrom)->subDay()->format('Y-m-d');
        
        $previousRevenue = \App\Models\Order::where('is_paid', 1)
            ->whereBetween('created_at', [$previousDateFrom, $previousDateTo . ' 23:59:59'])
            ->sum('total');
        
        $revenueGrowth = $previousRevenue > 0 
            ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 
            : 0;

        $previousOrders = \App\Models\Order::whereBetween('created_at', [$previousDateFrom, $previousDateTo . ' 23:59:59'])->count();
        $ordersGrowth = $previousOrders > 0 
            ? (($totalOrders - $previousOrders) / $previousOrders) * 100 
            : 0;

        // Recent Orders
        $recentOrders = \App\Models\Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Recent Voucher Orders
        $recentVoucherOrders = \App\Models\VouchersOrder::latest()
            ->take(5)
            ->get();

        // Monthly data for charts (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('F'),
                'month_ar' => $month->locale('ar')->translatedFormat('F'),
                'revenue' => \App\Models\Order::where('is_paid', 1)
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('total'),
                'orders' => \App\Models\Order::whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count(),
            ];
        }

        return [
            'orders' => [
                'total' => $totalOrders,
                'pending' => $pendingOrders,
                'delivered' => $deliveredOrders,
                'cancelled' => $cancelledOrders,
                'paid' => $paidOrders,
                'unpaid' => $unpaidOrders,
                'recent' => $recentOrders,
                'growth' => $ordersGrowth,
            ],
            'revenue' => [
                'total' => $totalRevenue,
                'today' => $todayRevenue,
                'month' => $monthRevenue,
                'week' => $weekRevenue,
                'average' => $avgOrderValue,
                'growth' => $revenueGrowth,
            ],
            'vouchers' => [
                'total' => $totalVoucherOrders,
                'pending' => $pendingVoucherOrders,
                'success' => $successVoucherOrders,
                'completed' => $completedVoucherOrders,
                'recent' => $recentVoucherOrders,
            ],
            'products' => [
                'total' => $totalProducts,
                'active' => $activeProducts,
            ],
            'users' => [
                'total' => $totalUsers,
                'new_this_month' => $newUsersThisMonth,
            ],
            'monthly' => $monthlyData,
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
