@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل الدور والصلاحيات')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="ti ti-shield-check me-2"></i>تعديل الدور: <span class="text-primary">{{ $role->name }}</span>
            </h4>
            <a href="{{ route('dashboard.roles.index') }}" class="btn btn-secondary">
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

        <form action="{{ route('dashboard.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">بيانات الدور الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold" for="name">اسم الدور (Role Name) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required {{ $role->name === 'Super Admin' ? 'readonly' : '' }}>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($role->name === 'Super Admin')
                                <small class="text-muted">الدور الرئيسي Super Admin لا يمكن تغيير اسمه</small>
                            @endif
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input cursor-pointer" type="checkbox" id="selectAllGlobal">
                                <label class="form-check-label fw-bold cursor-pointer" for="selectAllGlobal">تحديد كافة الصلاحيات في النظام (Select All)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permissions Matrix by Group -->
            <div class="row">
                @foreach ($permissionsGrouped as $groupName => $permissions)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3 border-bottom">
                                <span class="fw-bold text-primary fs-6">
                                    <i class="ti ti-folder me-1"></i>{{ $groupName }}
                                </span>
                                <div class="form-check">
                                    <input class="form-check-input group-select-all cursor-pointer" type="checkbox" id="group_{{ $loop->index }}" data-group="{{ $loop->index }}">
                                    <label class="form-check-label small cursor-pointer" for="group_{{ $loop->index }}">تحديد الكل</label>
                                </div>
                            </div>
                            <div class="card-body py-3">
                                @foreach ($permissions as $permKey => $permLabel)
                                    @php
                                        $isChecked = old('permissions') ? in_array($permKey, old('permissions')) : in_array($permKey, $rolePermissions);
                                    @endphp
                                    <div class="form-check mb-2">
                                        <input class="form-check-input perm-checkbox group-item-{{ $loop->parent->index }} cursor-pointer" 
                                               type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ $permKey }}" 
                                               id="perm_{{ $permKey }}"
                                               {{ $isChecked ? 'checked' : '' }}>
                                        <label class="form-check-label cursor-pointer" for="perm_{{ $permKey }}">
                                            {{ $permLabel }} <small class="text-muted">({{ $permKey }})</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="card mt-2">
                <div class="card-body d-flex justify-content-end gap-2">
                    <a href="{{ route('dashboard.roles.index') }}" class="btn btn-label-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-device-floppy me-1"></i>تحديث الدور
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('page-script')
    <script>
        $(document).ready(function() {
            // Global select all
            $('#selectAllGlobal').on('change', function() {
                var checked = $(this).is(':checked');
                $('.perm-checkbox').prop('checked', checked);
                $('.group-select-all').prop('checked', checked);
            });

            // Group select all
            $('.group-select-all').on('change', function() {
                var groupIdx = $(this).data('group');
                var checked = $(this).is(':checked');
                $('.group-item-' + groupIdx).prop('checked', checked);
            });
        });
    </script>
@endsection
