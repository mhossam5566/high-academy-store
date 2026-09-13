<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Group definitions for permissions display
     */
    public static function getPermissionsGrouped(): array
    {
        return [
            'لوحة التحكم' => [
                'view_dashboard' => 'عرض لوحة التحكم والإحصائيات',
            ],
            'إدارة المديرين' => [
                'view_admins' => 'عرض قائمة المديرين',
                'create_admins' => 'إضافة مدير جديد',
                'edit_admins' => 'تعديل بيانات المدير',
                'delete_admins' => 'حذف مدير',
            ],
            'الأدوار والصلاحيات' => [
                'view_roles' => 'عرض قائمة الأدوار والصلاحيات',
                'create_roles' => 'إضافة دور وصلاحيات جديدة',
                'edit_roles' => 'تعديل الدور والصلاحيات',
                'delete_roles' => 'حذف دور',
            ],
            'المدرسين' => [
                'view_teachers' => 'عرض قائمة المدرسين',
                'create_teachers' => 'إضافة مدرس',
                'edit_teachers' => 'تعديل مدرس',
                'delete_teachers' => 'حذف مدرس',
            ],
            'المراحل التعليمية' => [
                'view_stages' => 'عرض المراحل التعليمية',
                'create_stages' => 'إضافة مرحلة تعليمية',
                'edit_stages' => 'تعديل مرحلة تعليمية',
                'delete_stages' => 'حذف مرحلة تعليمية',
            ],
            'الصفوف الدراسية' => [
                'view_sliders' => 'عرض الصفوف الدراسية',
                'create_sliders' => 'إضافة صف دراسي',
                'edit_sliders' => 'تعديل صف دراسي',
                'delete_sliders' => 'حذف صف دراسي',
            ],
            'المواد الدراسية' => [
                'view_categories' => 'عرض المواد الدراسية',
                'create_categories' => 'إضافة مادة دراسية',
                'edit_categories' => 'تعديل مادة دراسية',
                'delete_categories' => 'حذف مادة دراسية',
            ],
            'الأقسام الرئيسية' => [
                'view_main_categories' => 'عرض الأقسام الرئيسية',
                'create_main_categories' => 'إضافة قسم رئيسي',
                'edit_main_categories' => 'تعديل قسم رئيسي',
                'delete_main_categories' => 'حذف قسم رئيسي',
            ],
            'الكتب والمنتجات' => [
                'view_products' => 'عرض قائمة الكتب',
                'create_products' => 'إضافة كتاب جديد',
                'edit_products' => 'تعديل كتاب',
                'delete_products' => 'حذف كتاب',
            ],
            'الطلبات' => [
                'view_orders' => 'عرض كل الطلبات',
                'create_library_orders' => 'الحجز من المكتبة (إنشاء طلب يدوي)',
                'edit_orders' => 'تعديل حالة وتفاصيل الطلب',
                'export_orders' => 'تصدير الطلبات (Excel / PDF)',
                'barcode_orders' => 'إدارة باركود الطلبات',
            ],
            'أكواد المدرسين (الكوبونات)' => [
                'view_coupons' => 'عرض أكواد المدرسين',
                'create_coupons' => 'إضافة كود جديد',
                'edit_coupons' => 'تعديل الكود',
                'delete_coupons' => 'حذف الكود',
            ],
            'الفاوتشرات' => [
                'view_vouchers' => 'عرض الفاوتشرات',
                'create_vouchers' => 'إضافة فاوتشر',
                'edit_vouchers' => 'تعديل فاوتشر',
                'delete_vouchers' => 'حذف فاوتشر',
            ],
            'طلبات الكوبونات' => [
                'view_voucher_orders' => 'عرض طلبات الكوبونات',
                'edit_voucher_orders' => 'تعديل حالة طلبات الكوبونات',
            ],
            'العروض' => [
                'view_offers' => 'عرض العروض',
                'create_offers' => 'إضافة عرض',
                'edit_offers' => 'تعديل عرض',
                'delete_offers' => 'حذف عرض',
            ],
            'الخصومات' => [
                'view_discounts' => 'عرض الخصومات',
                'create_discounts' => 'إضافة خصم',
                'edit_discounts' => 'تعديل خصم',
                'delete_discounts' => 'حذف خصم',
            ],
            'طرق الشحن' => [
                'view_shipping_methods' => 'عرض طرق الشحن',
                'create_shipping_methods' => 'إضافة طريقة شحن',
                'edit_shipping_methods' => 'تعديل طريقة شحن',
                'delete_shipping_methods' => 'حذف طريقة شحن',
            ],
            'كلمات البحث' => [
                'view_search_keywords' => 'عرض كلمات البحث',
                'create_search_keywords' => 'إضافة كلمة بحث',
                'edit_search_keywords' => 'تعديل كلمة بحث',
                'delete_search_keywords' => 'حذف كلمة بحث',
            ],
            'الأسئلة الشائعة' => [
                'view_faqs' => 'عرض الأسئلة الشائعة',
                'create_faqs' => 'إضافة سؤال شائع',
                'edit_faqs' => 'تعديل سؤال شائع',
                'delete_faqs' => 'حذف سؤال شائع',
            ],
            'المحافظات والمدن' => [
                'view_governorates' => 'عرض المحافظات والمدن',
                'create_governorates' => 'إضافة محافظة أو مدينة',
                'edit_governorates' => 'تعديل محافظة أو مدينة',
                'delete_governorates' => 'حذف محافظة أو مدينة',
            ],
            'تنبيهات الموقع' => [
                'view_notifications' => 'عرض تنبيهات الموقع',
                'create_notifications' => 'إضافة تنبيه جديد',
                'edit_notifications' => 'تعديل تنبيه',
                'delete_notifications' => 'حذف تنبيه',
            ],
            'إعدادات الموقع' => [
                'view_settings' => 'عرض الإعدادات العامة',
                'edit_settings' => 'تعديل الإعدادات العامة',
            ],
        ];
    }

    public function index()
    {
        return view('dashboard.pages.roles.index');
    }

    public function datatable()
    {
        $roles = Role::where('guard_name', 'admin')->withCount(['users', 'permissions'])->get();

        return DataTables::of($roles)
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('name', function ($row) {
                if ($row->name === 'Super Admin') {
                    return '<span class="badge bg-label-primary fs-6">' . e($row->name) . '</span>';
                }
                return '<span class="badge bg-label-secondary fs-6">' . e($row->name) . '</span>';
            })
            ->addColumn('users_count', function ($row) {
                return '<span class="badge bg-label-info">' . $row->users_count . ' مدير</span>';
            })
            ->addColumn('permissions_count', function ($row) {
                return '<span class="badge bg-label-success">' . $row->permissions_count . ' صلاحية</span>';
            })
            ->addColumn('actions', function ($row) {
                $editBtn = '<a href="' . route('dashboard.roles.edit', $row->id) . '" class="btn btn-sm btn-icon btn-label-primary me-1" title="تعديل"><i class="ti ti-edit"></i></a>';
                
                if ($row->name === 'Super Admin') {
                    $deleteBtn = '<button class="btn btn-sm btn-icon btn-label-secondary" disabled title="لا يمكن حذف الدور الرئيسي"><i class="ti ti-lock"></i></button>';
                } else {
                    $deleteBtn = '<button onclick="deleteRole(' . $row->id . ')" class="btn btn-sm btn-icon btn-label-danger" title="حذف"><i class="ti ti-trash"></i></button>';
                }

                return '<div class="d-flex justify-content-center align-items-center">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['name', 'users_count', 'permissions_count', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $permissionsGrouped = self::getPermissionsGrouped();
        return view('dashboard.pages.roles.create', compact('permissionsGrouped'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,NULL,id,guard_name,admin',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'اسم الدور مطلوب',
            'name.unique' => 'اسم هذا الدور موجود مسبقاً',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'admin',
        ]);

        if ($request->filled('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('dashboard.roles.index')->with('success', 'تم إنشاء الدور بنجاح');
    }

    public function edit(Role $role)
    {
        if ($role->guard_name !== 'admin') {
            abort(404);
        }

        $permissionsGrouped = self::getPermissionsGrouped();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('dashboard.pages.roles.edit', compact('role', 'permissionsGrouped', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->guard_name !== 'admin') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name,' . $role->id . ',id,guard_name,admin',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'اسم الدور مطلوب',
            'name.unique' => 'اسم هذا الدور موجود مسبقاً',
        ]);

        // Don't allow renaming Super Admin role to maintain consistency
        if ($role->name === 'Super Admin' && $request->name !== 'Super Admin') {
            return redirect()->back()->with('error', 'لا يمكن تغيير اسم الدور الرئيسي Super Admin');
        }

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        } else {
            $role->syncPermissions([]);
        }

        return redirect()->route('dashboard.roles.index')->with('success', 'تم تحديث الدور بنجاح');
    }

    public function destroy(Role $role)
    {
        if ($role->guard_name !== 'admin') {
            return response()->json(['success' => false, 'message' => 'الدور غير موجود']);
        }

        if ($role->name === 'Super Admin') {
            return response()->json(['success' => false, 'message' => 'لا يمكن حذف الدور الرئيسي Super Admin']);
        }

        $role->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف الدور بنجاح']);
    }
}
