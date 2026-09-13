<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class AdminManagementController extends Controller
{
    use ImageTrait;

    public function index()
    {
        return view('dashboard.pages.admins.index');
    }

    public function datatable()
    {
        $admins = Admin::with('roles')->get();

        return DataTables::of($admins)
            ->addColumn('id', function ($row) {
                return $row->id;
            })
            ->addColumn('admin_info', function ($row) {
                $photo = $row->photo ? asset('images/admin/' . $row->photo) : asset('dashboard/assets/img/avatars/1.png');
                return '<div class="d-flex justify-content-start align-items-center user-name">
                            <div class="avatar-wrapper">
                                <div class="avatar avatar-sm me-3">
                                    <img src="' . $photo . '" alt="Avatar" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <span class="fw-semibold text-body">' . e($row->name) . '</span>
                                <small class="text-muted">' . e($row->email) . '</small>
                            </div>
                        </div>';
            })
            ->addColumn('roles', function ($row) {
                $rolesHtml = '';
                foreach ($row->roles as $role) {
                    $badgeClass = $role->name === 'Super Admin' ? 'bg-label-primary' : 'bg-label-info';
                    $rolesHtml .= '<span class="badge ' . $badgeClass . ' me-1">' . e($role->name) . '</span>';
                }
                return $rolesHtml ?: '<span class="badge bg-label-secondary">بدون دور</span>';
            })
            ->addColumn('actions', function ($row) {
                $editBtn = '<a href="' . route('dashboard.admins.edit', $row->id) . '" class="btn btn-sm btn-icon btn-label-primary me-1" title="تعديل"><i class="ti ti-edit"></i></a>';
                
                if (auth('admin')->id() === $row->id) {
                    $deleteBtn = '<button class="btn btn-sm btn-icon btn-label-secondary" disabled title="لا يمكنك حذف حسابك الحالي"><i class="ti ti-user-x"></i></button>';
                } else {
                    $deleteBtn = '<button onclick="deleteAdmin(' . $row->id . ')" class="btn btn-sm btn-icon btn-label-danger" title="حذف"><i class="ti ti-trash"></i></button>';
                }

                return '<div class="d-flex justify-content-center align-items-center">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['admin_info', 'roles', 'actions'])
            ->toJson();
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $permissionsGrouped = RoleController::getPermissionsGrouped();
        return view('dashboard.pages.admins.create', compact('roles', 'permissionsGrouped'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'photo.image' => 'الملف يجب أن يكون صورة',
        ]);

        $photoName = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/admin'), $photoName);
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $photoName,
        ]);

        if ($request->filled('roles')) {
            $admin->syncRoles($request->roles);
        }

        if ($request->filled('permissions')) {
            $admin->syncPermissions($request->permissions);
        }

        return redirect()->route('dashboard.admins.index')->with('success', 'تم إنشاء حساب المدير بنجاح');
    }

    public function edit(Admin $admin)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $permissionsGrouped = RoleController::getPermissionsGrouped();
        $adminRoles = $admin->roles->pluck('name')->toArray();
        $adminPermissions = $admin->permissions->pluck('name')->toArray();

        return view('dashboard.pages.admins.edit', compact('admin', 'roles', 'permissionsGrouped', 'adminRoles', 'adminPermissions'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,' . $admin->id,
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'roles' => 'nullable|array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ], [
            'name.required' => 'الاسم مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صالح',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً',
            'password.min' => 'كلمة المرور يجب ألا تقل عن 6 أحرف',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'photo.image' => 'الملف يجب أن يكون صورة',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('images/admin'), $photoName);
            $data['photo'] = $photoName;
        }

        $admin->update($data);

        if ($request->has('roles')) {
            $admin->syncRoles($request->roles);
        } else {
            $admin->syncRoles([]);
        }

        if ($request->has('permissions')) {
            $admin->syncPermissions($request->permissions);
        } else {
            $admin->syncPermissions([]);
        }

        return redirect()->route('dashboard.admins.index')->with('success', 'تم تحديث بيانات المدير بنجاح');
    }

    public function destroy(Admin $admin)
    {
        if (auth('admin')->id() === $admin->id) {
            return response()->json(['success' => false, 'message' => 'لا يمكنك حذف حسابك الحالي المسجل به']);
        }

        $admin->delete();

        return response()->json(['success' => true, 'message' => 'تم حذف حساب المدير بنجاح']);
    }
}
