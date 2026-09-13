@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل بيانات المدير')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="ti ti-user-edit me-2"></i>تعديل بيانات المدير: <span class="text-primary">{{ $admin->name }}</span>
            </h4>
            <a href="{{ route('dashboard.admins.index') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-back-up me-1"></i>رجوع
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('dashboard.admins.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Basic Info Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">البيانات الأساسية للمدير</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="name">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="email">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="password">كلمة المرور الجديدة (اتركها فارغة إذا لم ترغب في التغيير)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="password_confirmation">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="photo">الصورة الشخصية</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                            @if($admin->photo)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <img src="{{ asset('images/admin/' . $admin->photo) }}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                    <span class="text-muted small">الصورة الحالية</span>
                                </div>
                            @endif
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles Assignment Card -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0 text-primary">
                        <i class="ti ti-shield me-2"></i>تعيين الأدوار والرتب (Roles)
                    </h5>
                </div>
                <div class="card-body pt-3">
                    <p class="text-muted small">اختر دوراً أو أكثر لتطبيقه على هذا المدير:</p>
                    <div class="row">
                        @forelse($roles as $role)
                            @php
                                $isRoleChecked = old('roles') ? in_array($role->name, old('roles')) : in_array($role->name, $adminRoles);
                            @endphp
                            <div class="col-md-4 mb-2">
                                <div class="form-check custom-option custom-option-basic p-2 border rounded">
                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}" {{ $isRoleChecked ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold cursor-pointer" for="role_{{ $role->id }}">
                                        {{ $role->name }}
                                        <span class="badge bg-label-info ms-1">{{ $role->permissions->count() }} صلاحية</span>
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-muted">لا توجد أدوار مضافة بعد.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Direct Permissions (Optional) Accordion / Section -->
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-secondary">
                        <i class="ti ti-key me-2"></i>صلاحيات مباشرة إضافية (Direct Permissions - اختياري)
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#directPermissionsSection" aria-expanded="false">
                        إظهار / إخفاء الصلاحيات المباشرة
                    </button>
                </div>
                <div class="collapse" id="directPermissionsSection">
                    <div class="card-body">
                        <div class="row">
                            @foreach ($permissionsGrouped as $groupName => $permissions)
                                <div class="col-md-6 mb-3">
                                    <div class="border rounded p-3 h-100 bg-white shadow-sm">
                                        <h6 class="text-primary fw-bold mb-2">{{ $groupName }}</h6>
                                        @foreach ($permissions as $permKey => $permLabel)
                                            @php
                                                $isPermChecked = old('permissions') ? in_array($permKey, old('permissions')) : in_array($permKey, $adminPermissions);
                                            @endphp
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permKey }}" id="direct_perm_{{ $permKey }}" {{ $isPermChecked ? 'checked' : '' }}>
                                                <label class="form-check-label small cursor-pointer" for="direct_perm_{{ $permKey }}">
                                                    {{ $permLabel }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.admins.index') }}" class="btn btn-label-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-device-floppy me-1"></i>تحديث المدير
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
