@extends('dashboard.layouts.layoutMaster')

@section('title', 'تعديل التنبيه')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1">تعديل التنبيه</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard.site_notifications.index') }}">تنبيهات الموقع</a></li>
                    <li class="breadcrumb-item active">تعديل التنبيه</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('dashboard.site_notifications.index') }}" class="btn btn-secondary">
            <i class="ti ti-arrow-right me-1"></i>العودة للقائمة
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('dashboard.site_notifications.update', $notification->id) }}" method="POST" id="notificationForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="content">المحتوى <span class="text-danger">*</span></label>
                    <div id="full-editor">{!! $notification->content !!}</div>
                    <textarea name="content" id="content-textarea" style="display:none;"></textarea>
                    @error('content')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="is_active">الحالة</label>
                    <select name="is_active" id="is_active" class="form-select">
                        <option value="1" {{ $notification->is_active ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ !$notification->is_active ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('page-script')
    <script>
        $(function() {
            const fullEditor = new Quill('#full-editor', {
                bounds: '#full-editor',
                placeholder: 'أدخل محتوى التنبيه هنا...',
                modules: {
                    formula: true,
                    toolbar: [
                        [{ font: [] }, { size: [] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ color: [] }, { background: [] }],
                        [{ script: 'super' }, { script: 'sub' }],
                        [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
                        [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
                        [{ direction: 'rtl' }],
                        ['link', 'image', 'video', 'formula'],
                        ['clean']
                    ]
                },
                theme: 'snow'
            });

            $('#notificationForm').on('submit', function() {
                $('#content-textarea').val(fullEditor.root.innerHTML);
            });
        });
    </script>
@endsection
