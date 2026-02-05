<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Brand;
use Illuminate\Support\Str;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    use MediaHandler;
    public function index()
    {
        return view('dashboard.pages.coupon.index');
    }

    public function datatable()
    {
        $coupons = Coupon::with('brand')->get();
        $coupons = $coupons->reverse();
        return DataTables::of($coupons)
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('price', function ($row) {
                return $row->price;
            })
            ->addColumn('brand', function ($row) {
                return $row->brand ? $row->brand->title : '<span class="text-muted">-</span>';
            })
            ->addColumn('image', function ($row) {
                if ($row->image !== null) {
                    $image = '<img src="' . url('storage/' . $row->image) . '" alt="coupon-image" style="height:80px;width:100px" class="rounded">';
                    return $image;
                } else {
                    return null;
                }
            })
            ->addColumn('count', function ($row) {
                return $row->vouchers->where('is_used', 0)->count();
            })
            ->addColumn('operation', function ($row) {
                $edit = '<a href="' . route('dashboard.coupons.edit', $row->id) . '" class="btn btn-sm btn-primary me-1">
                <i class="ti ti-edit me-1"></i>تعديل
            </a>';
                $delete = '<a coupon_id="' . $row->id . '" class="btn btn-sm btn-danger me-1 delete_btn">
                <i class="ti ti-trash me-1"></i>حذف
            </a>';
                $add = '<a href="' . route('dashboard.vouchers', $row->id) . '" class="btn btn-sm btn-info">
                <i class="ti ti-code me-1"></i>إدارة الأكواد
            </a>';
                return $edit . $delete . $add;
            })
            ->rawColumns(['operation' => 'operation', 'image' => 'image', 'count' => 'count', 'brand' => 'brand'])
            ->toJson();
    }

    public function add()
    {
        $brands = Brand::orderBy('id', 'DESC')->get();
        return view('dashboard.pages.coupon.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:weekly,monthly,package',
            'brand_id' => 'nullable|exists:brands,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = self::upload($request->file('image'), 'images/coupons');
        }

        $coupon = Coupon::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
            'type' => $request->type,
            'brand_id' => $request->brand_id
        ]);

        return response()->json([
            'message' => 'تم حفظ الكوبون بنجاح',
            'coupon' => $coupon
        ], 201);
    }


    public function edit(Coupon $coupon)
    {
        $brands = Brand::orderBy('id', 'DESC')->get();
        return view('dashboard.pages.coupon.edit', compact('coupon', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:weekly,monthly,package',
            'brand_id' => 'nullable|exists:brands,id',
            'delete_image' => 'nullable|boolean' // حقل اختياري لإزالة الصورة
        ]);

        $coupon = Coupon::findOrFail($id);

        // إزالة الصورة إذا تم إرسال delete_image = true
        if ($request->delete_image) {
            if ($coupon->image) {
                self::deleteMedia($coupon->image);
            }
            $coupon->image = null;
        }

        if ($request->hasFile('image')) {
            if ($coupon->image) {
                self::deleteMedia($coupon->image);
            }
            $coupon->image = self::upload($request->file('image'), 'images/coupons');
        }

        $coupon->name = $request->name;
        $coupon->price = $request->price;
        $coupon->type = $request->type;
        $coupon->brand_id = $request->brand_id;
        $coupon->save();

        return response()->json([
            'message' => 'تم تحديث الكوبون بنجاح',
            'coupon' => $coupon,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:coupons,id',
        ]);


        $coupon = Coupon::findOrFail($request->id);


        if ($coupon->image) {
            self::deleteMedia($coupon->image);
        }


        $coupon->delete();


        return response()->json([
            'id' => $request->id,
            'message' => 'تم حذف الكوبون بنجاح',
        ]);
    }


}
