<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $guard = 'admin';

        $permissionsByGroup = [
            'لوحة التحكم' => [
                'view_dashboard_stats' => 'عرض إحصائيات الشاشة الرئيسية',
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

        $allPermissionNames = [];

        foreach ($permissionsByGroup as $group => $permissions) {
            foreach ($permissions as $name => $displayName) {
                Permission::findOrCreate($name, $guard);
                $allPermissionNames[] = $name;
            }
        }

        // Create or find Super Admin role
        $superAdminRole = Role::findOrCreate('Super Admin', $guard);
        $superAdminRole->syncPermissions($allPermissionNames);

        // Assign Super Admin role to all existing admins if any
        try {
            $admins = Admin::all();
            foreach ($admins as $admin) {
                if (!$admin->hasRole('Super Admin')) {
                    $admin->assignRole($superAdminRole);
                }
            }
        } catch (\Exception $e) {
            // In case DB is not yet migrated/connected during testing
        }
    }
}
