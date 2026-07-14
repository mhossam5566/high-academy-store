@extends('dashboard.layouts.layoutMaster')

@section('title', 'إعدادات الموقع')

@section('vendor-style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">إعدادات الموقع</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active">إعدادات الموقع</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Alert Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible d-flex align-items-center" role="alert">
            <span class="alert-icon text-success me-2">
                <i class="ti ti-check ti-xs"></i>
            </span>
            <div class="d-flex flex-column ps-1">
                <h6 class="alert-heading mb-1">نجاح!</h6>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="ti ti-settings me-2"></i>إعدادات الواتساب والتواصل الاجتماعي
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('dashboard.settings.update') }}" method="POST" id="settingsForm">
                @csrf

                <div class="row">
                    <!-- WhatsApp Number -->
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_number" class="form-label">رقم الواتساب <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                            <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" 
                                id="whatsapp_number" name="whatsapp_number" 
                                value="{{ old('whatsapp_number', $settings['whatsapp_number']) }}" 
                                placeholder="مثال: 201550234324" required>
                        </div>
                        @error('whatsapp_number')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                        <div class="form-text">أدخل الرقم بدون علامة + ومسبوقاً بكود الدولة (مثال: 201550234324 لمصر).</div>
                    </div>

                    <!-- WhatsApp Channel -->
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_channel" class="form-label">رابط قناة الواتساب</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="ti ti-link"></i></span>
                            <input type="url" class="form-control @error('whatsapp_channel') is-invalid @enderror" 
                                id="whatsapp_channel" name="whatsapp_channel" 
                                value="{{ old('whatsapp_channel', $settings['whatsapp_channel']) }}" 
                                placeholder="https://www.whatsapp.com/channel/..." >
                        </div>
                        @error('whatsapp_channel')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                        <div class="form-text">رابط القناة الخاص بالواتساب لنشر التحديثات.</div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i>حفظ الإعدادات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('page-script')
    @if (session('success'))
        <script>
            $(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success("{{ session('success') }}");
                }
            });
        </script>
    @endif
@endsection
