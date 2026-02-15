<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coupon;
use App\Models\Voucher;
use Illuminate\Support\Str;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class VoucherController extends Controller
{
    use MediaHandler;
    public function index(Coupon $coupon)
    {

        return view('dashboard.pages.voucher.index', compact('coupon'));
    }

    public function datatable(Coupon $coupon)
    {
        $vouchers = $coupon->vouchers()->with('user')->get();
        $vouchers = $vouchers->reverse();
        return DataTables::of($vouchers)
            ->addColumn('code', function ($row) {
                return $row->code;
            })
            ->addColumn('image', function ($row) {
                if ($row->image !== null) {
                    $image = '<img src="' . url('storage/' . $row->image) . '" alt="coupon-image" style="height:80px;width:100px" class="rounded">';
                    return $image;
                } else {
                    return null;
                }
            })
            ->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '<span class="text-muted">غير محدد</span>';
            })
            ->addColumn('user_phone', function ($row) {
                return $row->user ? $row->user->phone : '<span class="text-muted">غير محدد</span>';
            })
            ->addColumn('state', function ($row) {
                if ($row->is_used) {
                    return '<span class="badge bg-danger">مستخدم</span>';
                } else {
                    return '<span class="badge bg-success">غير مستخدم</span>';
                }
            })

            ->addColumn('operation', function ($row) {
                $edit = '<a href="' . route('dashboard.vouchers.edit', $row->id) . '" class="btn btn-sm btn-primary me-1">
                <i class="ti ti-edit me-1"></i>تعديل
            </a>';
                $delete = '<a data-id="' . $row->id . '" class="btn btn-sm btn-danger delete_btn">
                <i class="ti ti-trash me-1"></i>حذف
            </a>';
                return $edit . $delete;
            })
            ->rawColumns(['operation' => 'operation', 'state' => 'state', "image" => "image", 'user_name' => 'user_name', 'user_phone' => 'user_phone'])
            ->toJson();
    }

    public function add(Coupon $coupon)
    {
        return view('dashboard.pages.voucher.create', compact('coupon'));
    }

    public function store(Coupon $coupon, Request $request)
    {

        $request->validate([
            'code' => 'required_without:image|string|max:255',
            'image' => 'required_without:code|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = self::upload($request->file('image'), 'images/vouchers');
        }

        $voucher = $coupon->vouchers()->create([
            'code' => $request->code,
            'image' => $imagePath
        ]);


        // إرجاع استجابة JSON بنجاح
        return response()->json([
            'message' => 'تم حفظ الكود بنجاح',
            'coupon' => $voucher
        ], 201);
    }

    public function edit(Voucher $voucher)
    {
        return view('dashboard.pages.voucher.edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'code' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_image' => 'nullable|boolean' // حقل اختياري لإزالة الصورة
        ]);

        $voucher = Voucher::findOrFail($id);

        // تحديث الكود حتى لو تم إرساله فارغًا
        if ($request->has('code')) {
            $voucher->code = $request->code;
        }

        // إزالة الصورة إذا تم إرسال delete_image = true
        if ($request->delete_image) {
            if ($voucher->image) {
                self::deleteMedia($voucher->image);
            }
            $voucher->image = null; // إزالة المسار من قاعدة البيانات
        }

        // التحقق من الصورة الجديدة
        if ($request->hasFile('image')) {
            if ($voucher->image) {
                self::deleteMedia($voucher->image);
            }
            $voucher->image = self::upload($request->file('image'), 'images/vouchers');
        }

        // حفظ التغييرات
        $voucher->save();

        // إرجاع الاستجابة
        return response()->json([
            'message' => 'تم تحديث الكوبون بنجاح',
            'voucher' => $voucher,
        ]);
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:vouchers,id',
        ]);


        $voucher = Voucher::findOrFail($request->id);
        if ($voucher->image) {
            self::deleteMedia($voucher->image);
        }
        $voucher->delete();


        return response()->json([
            'id' => $request->id,
            'message' => 'تم حذف الكوبون بنجاح',
        ]);
    }

}
