@extends('dashboard.layouts.layoutMaster')

@section('title', 'إضافة طريقة شحن')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css">
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <span class="text-muted fw-light">طرق الشحن /</span> إضافة طريقة جديدة
            </h4>
            <a href="{{ route('dashboard.shipping-methods') }}" class="btn btn-secondary">
                <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
            </a>
        </div>

        <!-- Create Shipping Method Card -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-truck-delivery me-2"></i>معلومات طريقة الشحن
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('dashboard.shipping-methods.store') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-4">
                                <label class="form-label" for="name">
                                    <i class="ti ti-tag me-1"></i>الاسم
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" id="name" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required placeholder="مثال: شحن القاهرة">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div class="mb-4">
                                <label class="form-label" for="type">
                                    <i class="ti ti-category me-1"></i>نوع الشحن
                                    <span class="text-danger">*</span>
                                </label>
                                <select id="type" name="type"
                                    class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="">اختر النوع</option>
                                    <option value="post" {{ old('type') == 'post' ? 'selected' : '' }}>مكتب بريد</option>
                                    <option value="home" {{ old('type') == 'home' ? 'selected' : '' }}>توصيل لباب البيت
                                    </option>
                                    <option value="branch" {{ old('type') == 'branch' ? 'selected' : '' }}>استلام من المكتبة
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fee -->
                            <div class="mb-4">
                                <label class="form-label" for="fee">
                                    <i class="ti ti-coin me-1"></i>رسوم الخدمة (جنيه)
                                </label>
                                <input type="number" id="fee" name="fee" step="0.01"
                                    class="form-control @error('fee') is-invalid @enderror" value="{{ old('fee', '0.00') }}"
                                    placeholder="0.00">
                                @error('fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Governorate -->
                            <div class="mb-4">
                                <label class="form-label" for="government">
                                    <i class="ti ti-map-pin me-1"></i>المحافظة
                                </label>
                                <select id="government" name="government"
                                    class="form-select @error('government') is-invalid @enderror">
                                    <option value="">اختر المحافظة</option>
                                    @foreach ($govs as $g)
                                        <option value="{{ $g['id'] }}"
                                            {{ old('government') == $g['id'] ? 'selected' : '' }}>
                                            {{ $g['governorate_name_ar'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('government')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="form-label" for="address">
                                    <i class="ti ti-home me-1"></i>العنوان
                                </label>
                                <input type="text" id="address" name="address"
                                    class="form-control @error('address') is-invalid @enderror"
                                    value="{{ old('address') }}" placeholder="مثال: شارع التحرير، ميدان رمسيس">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phones -->
                            <div class="mb-4">
                                <label class="form-label" for="phones">
                                    <i class="ti ti-phone me-1"></i>أرقام الهاتف
                                </label>
                                <input type="text" id="phones" name="phones"
                                    class="form-control @error('phones') is-invalid @enderror" value="{{ old('phones') }}"
                                    placeholder="أضف رقماً ثم اضغط Enter">
                                @error('phones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">اضغط Enter بعد كل رقم لإضافته</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti ti-device-floppy me-1"></i>حفظ طريقة الشحن
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
    <script>
        $(function() {
            $('#phones').tagsInput({
                width: '100%',
                height: '75px',
                interactive: true,
                defaultText: 'أضف رقم',
                removeWithBackspace: true,
                minChars: 1,
                maxChars: 15,
                placeholderColor: '#666'
            });
        });
    </script>
@endsection

@section('page-style')
    <style>
        .tagsinput {
            border: 1px solid #d9dee3;
            border-radius: 0.375rem;
            padding: 0.5rem;
            min-height: 75px;
        }

        .tagsinput .tag {
            background: var(--bs-primary);
            color: white;
            border-radius: 0.25rem;
            padding: 0.25rem 0.5rem;
            margin: 0.25rem;
            display: inline-block;
        }

        .tagsinput .tag a {
            color: white;
            margin-left: 0.5rem;
        }
    </style>
@endsection
