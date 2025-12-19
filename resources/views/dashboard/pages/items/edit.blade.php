@extends('dashboard.layouts.layoutMaster')

@section('title', __('dashboard.edit_product'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/tagify/tagify.css') }}" />
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.css') }}">
@endsection

@section('vendor-script')
    <script src="{{ asset('dashboard/assets/vendor/libs/tagify/tagify.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('dashboard/assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('dashboard/assets/js/form-ajax.js') }}"></script>
    <script>
        // نفس سكريبت إنشاء الخيارات الديناميكي للأحجام و الألوان
        function updateSelectOptions() {
            const sizes = [];
            const colors = [];

            // التقاط الحقول سواء قبل أو بعد تفعيل الريبيتر (الاسم الخام أو المصفوفي)
            document.querySelectorAll(
                '#size-repeater [data-repeater-item] input[name="size"], #size-repeater [data-repeater-item] input[name$="[size]"]'
            ).forEach(function(input) {
                const value = (input.value || '').trim();
                if (value && !sizes.includes(value)) sizes.push(value);
            });

            document.querySelectorAll(
                '#color-repeater [data-repeater-item] input[name="color"], #color-repeater [data-repeater-item] input[name$="[color]"]'
            ).forEach(function(input) {
                const value = (input.value || '').trim();
                if (value && !colors.includes(value)) colors.push(value);
            });

            document.querySelectorAll('.size-select').forEach(function(select) {
                const previouslySelected = select.value;
                select.innerHTML = '<option value="">{{ __('dashboard.choose') }}</option>';
                sizes.forEach(function(size) {
                    const opt = document.createElement('option');
                    opt.value = size;
                    opt.textContent = size;
                    if (size === previouslySelected) opt.selected = true;
                    select.appendChild(opt);
                });
            });

            document.querySelectorAll('.color-select').forEach(function(select) {
                const previouslySelected = select.value;
                select.innerHTML = '<option value="">{{ __('dashboard.choose') }}</option>';
                colors.forEach(function(color) {
                    const opt = document.createElement('option');
                    opt.value = color;
                    opt.textContent = color;
                    if (color === previouslySelected) opt.selected = true;
                    select.appendChild(opt);
                });
            });
        }

        document.addEventListener('input', function(e) {
            if (e.target.matches('input[name$="[size]"]') || e.target.matches('input[name$="[color]"]')) {
                updateSelectOptions();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // تأجيل بسيط لضمان اكتمال تهيئة الريبيتر
            setTimeout(updateSelectOptions, 120);
        });
    </script>
    <script>
        // Tagify مع تحميل الكلمات الحالية للعنصر
        const keywordsInput = document.querySelector('#keywords');
        const whitelist = {!! json_encode($keywords->pluck('keyword')->toArray()) !!};
        const existingKeywords = {!! json_encode(json_decode($item->keywords, true) ?? []) !!};
        const tagifyInstance = new Tagify(keywordsInput, {
            whitelist: whitelist,
            maxTags: 10,
            dropdown: {
                maxItems: 20,
                classname: 'tags-inline',
                enabled: 0,
                closeOnSelect: false
            }
        });
        tagifyInstance.addTags(existingKeywords);

        // تهيئة الـ repeater
        $(function() {
            const setupRepeater = (selector, confirmMessage = 'هل أنت متأكد؟') => {
                $(selector).repeater({
                    show: function() {
                        $(this).slideDown();
                        setTimeout(() => {
                            $(this).find('input').first().blur();
                            updateSelectOptions();
                        }, 50);
                    },
                    hide: function(deleteElement) {
                        if (confirm(confirmMessage)) {
                            $(this).slideUp(deleteElement);
                            setTimeout(updateSelectOptions, 50);
                        }
                    }
                });
            };
            setupRepeater('.form-repeater:not(#size-repeater):not(#color-repeater)',
                'هل أنت متأكد من حذف هذا العنصر؟');
            setupRepeater('#size-repeater', 'هل أنت متأكد من حذف هذا المقاس؟');
            setupRepeater('#color-repeater', 'هل أنت متأكد من حذف هذا اللون؟');
            setupRepeater('#images-repeater', 'هل أنت متأكد من حذف هذه الصورة؟');
            // تحديث بعد تهيئة المكررات
            setTimeout(updateSelectOptions, 150);
        });
    </script>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center py-3 mb-4">
        <h4 class="mb-0">
            <span class="text-muted fw-light">{{ __('dashboard.Products') }} /</span> {{ __('dashboard.edit') }}
        </h4>
    </div>

    <form action="{{ route('dashboard.items.update', $item->id) }}" method="POST" data-validate class="needs-validation"
        novalidate data-ajax data-redirect="{{ route('dashboard.items.index') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- البيانات الأساسية -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.basic_info') }}</h5>
            <div class="card-body">
                <div class="tab-content pt-3">
                    <ul class="nav nav-tabs" role="tablist">
                        @foreach (config('app.supported_langs') as $lang => $label)
                            <li class="nav-item">
                                <a class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab"
                                    href="#edit-product-{{ $lang }}" role="tab">{{ strtoupper($label) }}</a>
                            </li>
                        @endforeach
                    </ul>

                    @foreach (config('app.supported_langs') as $lang => $label)
                        <div class="tab-pane fade @if ($loop->first) show active @endif mt-3"
                            id="edit-product-{{ $lang }}" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('dashboard.item_name') }}
                                        ({{ strtoupper($label) }})
                                    </label>
                                    <input type="text" class="form-control"
                                        name="translations[{{ $lang }}][name]"
                                        value="{{ $item->getTranslation('name', $lang) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('dashboard.item_short_description') }}
                                        ({{ strtoupper($label) }})</label>
                                    <input type="text" class="form-control"
                                        name="translations[{{ $lang }}][short_description]"
                                        value="{{ $item->getTranslation('short_description', $lang) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('dashboard.item_description') }}
                                        ({{ strtoupper($label) }})</label>
                                    <textarea class="form-control" name="translations[{{ $lang }}][description]" rows="3">{{ $item->getTranslation('description', $lang) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">{{ __('dashboard.return_policy') }}
                                        ({{ strtoupper($label) }})</label>
                                    <textarea class="form-control" name="translations[{{ $lang }}][return_policy]" rows="3">{{ $item->getTranslation('return_policy', $lang) }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- السعر والخصم -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.discount') }}</h5>
            <div class="card-body">
                <div class="row g-3">

                    {{-- <div class="col-md-4">
                        <label for="price" class="form-label">{{ __('dashboard.item_price') }}</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">EGP</span>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                value="{{ $item->price }}" required>
                        </div>
                    </div> --}}

                    <div class="col-md-6">
                        <label for="discount_type" class="form-label">{{ __('dashboard.discount_type') }}</label>
                        <select name="discount_type" id="discount_type" class="form-select">
                            <option value="">{{ __('dashboard.without_discount') }}</option>
                            <option value="fixed" @selected($item->discount_type == 'fixed')>{{ __('dashboard.fixed') }}</option>
                            <option value="percentage" @selected($item->discount_type == 'percentage')>{{ __('dashboard.percentage') }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="discount" class="form-label">{{ __('dashboard.discount_value') }}</label>
                        <div class="input-group input-group-merge">
                            <span class="input-group-text">EGP</span>
                            <input type="number" step="0.01" class="form-control" id="discount" name="discount"
                                value="{{ $item->discount }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- التصنيف والعلامة التجارية -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.category_and_brand') }}</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="brand_id" class="form-label">{{ __('dashboard.brand') }}</label>
                        <select name="brand_id" id="brand_id" class="form-select">
                            <option value="">-- {{ __('dashboard.choose') }} --</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" @selected($brand->id == $item->brand_id)>
                                    {{ $brand->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">{{ __('dashboard.category') }}</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">-- {{ __('dashboard.choose') }} --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" @selected($cat->id == $item->category_id)>
                                    {{ $cat->getTranslation('name') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- المخزون والإعدادات -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.stock_and_settings') }}</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="stock" class="form-label">{{ __('dashboard.item_stock') }}</label>
                        <input type="number" class="form-control" id="stock" name="stock"
                            value="{{ $item->stock }}">
                    </div>
                    <div class="col-md-6">
                        <label for="hashtag" class="form-label">{{ __('dashboard.hashtag') }}</label>
                        <select name="hashtag" id="hashtag" class="form-select">
                            <option value="">-- {{ __('dashboard.without') }} --</option>
                            @for ($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}" @selected($item->hashtag == $i)>#{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block mb-1">{{ __('dashboard.is_stock') }}</label>
                        <label class="switch">
                            <input type="checkbox" class="switch-input" name="is_stock" value="1"
                                @checked($item->is_stock)>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="ti ti-check"></i></span>
                                <span class="switch-off"><i class="ti ti-x"></i></span>
                            </span>
                            <span class="switch-label">{{ __('dashboard.yes-no') }}</span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block mb-1">{{ __('dashboard.is_express') }}</label>
                        <label class="switch">
                            <input type="checkbox" class="switch-input" name="is_express" value="1"
                                @checked($item->is_express)>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="ti ti-check"></i></span>
                                <span class="switch-off"><i class="ti ti-x"></i></span>
                            </span>
                            <span class="switch-label">{{ __('dashboard.yes-no') }}</span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label d-block mb-1">{{ __('dashboard.item_status') }}</label>
                        <label class="switch">
                            <input type="checkbox" class="switch-input" name="status" value="active"
                                @checked($item->status == 'active')>
                            <span class="switch-toggle-slider">
                                <span class="switch-on"><i class="ti ti-check"></i></span>
                                <span class="switch-off"><i class="ti ti-x"></i></span>
                            </span>
                            <span class="switch-label">{{ __('dashboard.active-inactive') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- الكلمات المفتاحية -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.keywords') }}</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="keywords" class="form-label">{{ __('dashboard.keywords') }}</label>
                        <input id="keywords" name="keywords" class="form-control"
                            placeholder="{{ __('dashboard.add_keywords') }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- الألوان -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.item_colors') }}</h5>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label d-block mb-2">{{ __('dashboard.item_colors') }}</label>
                        <div class="form-repeater d-flex align-items-center flex-wrap gap-2" id="color-repeater">
                            <div data-repeater-list="colors" class="d-flex flex-wrap gap-2">
                                @forelse(($item->colors ?? []) as $color)
                                    <div data-repeater-item class="d-flex align-items-center gap-2">
                                        <input type="color" name="color" class="form-control form-control-color"
                                            value="{{ $color->color_code ?? '#000000' }}" />
                                        <button type="button" data-repeater-delete
                                            class="btn btn-sm btn-danger">-</button>
                                    </div>
                                @empty
                                    <div data-repeater-item class="d-flex align-items-center gap-2">
                                        <input type="color" name="color" class="form-control form-control-color" />
                                        <button type="button" data-repeater-delete
                                            class="btn btn-sm btn-danger">-</button>
                                    </div>
                                @endforelse
                            </div>
                            <button type="button" data-repeater-create
                                class="btn btn-sm btn-primary">{{ __('dashboard.add_color') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المقاسات والأسعار -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.item_sizes') }}</h5>
            <div class="card-body">
                <div class="form-repeater" id="size-repeater">
                    <div data-repeater-list="sizes">
                        @php $sizesCollection = $item->sizes ?? collect(); @endphp
                        @forelse($sizesCollection as $size)
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="mb-3 col-md-5 col-12 mb-0">
                                        <label class="form-label">{{ __('dashboard.item_size') }}</label>
                                        <input type="text" name="size" class="form-control"
                                            value="{{ $size->name ?? '' }}" placeholder="مثال: M أو 42" />
                                    </div>
                                    <div class="mb-3 col-md-5 col-12 mb-0">
                                        <label class="form-label">{{ __('dashboard.size_price') }}</label>
                                        <input type="number" step="0.01" name="price" class="form-control"
                                            value="{{ $size->price ?? 0 }}" placeholder="00.00" />
                                    </div>
                                    <div class="mb-3 col-md-2 col-12 d-flex align-items-center mb-0">
                                        <button class="btn btn-label-danger mt-4" data-repeater-delete type="button">
                                            <i class="ti ti-x ti-xs me-1"></i>
                                            <span class="align-middle">{{ __('dashboard.delete') }}</span>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @empty
                            <div data-repeater-item>
                                <div class="row">
                                    <div class="mb-3 col-md-5 col-12 mb-0">
                                        <label class="form-label">{{ __('dashboard.item_size') }}</label>
                                        <input type="text" name="size" class="form-control"
                                            placeholder="مثال: M أو 42" />
                                    </div>
                                    <div class="mb-3 col-md-5 col-12 mb-0">
                                        <label class="form-label">{{ __('dashboard.size_price') }}</label>
                                        <input type="number" step="0.01" name="price" class="form-control"
                                            placeholder="00.00" />
                                    </div>
                                    <div class="mb-3 col-md-2 col-12 d-flex align-items-center mb-0">
                                        <button class="btn btn-label-danger mt-4" data-repeater-delete type="button">
                                            <i class="ti ti-x ti-xs me-1"></i>
                                            <span class="align-middle">{{ __('dashboard.delete') }}</span>
                                        </button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endforelse
                    </div>
                    <div class="mb-0">
                        <button class="btn btn-primary" data-repeater-create type="button">
                            <i class="ti ti-plus me-1"></i>
                            <span class="align-middle">{{ __('dashboard.add_size') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- صور المنتج -->
        <div class="card mb-4">
            <h5 class="card-header">{{ __('dashboard.item_images') }}</h5>
            <div class="card-body">
                <div class="form-repeater" id="images-repeater">
                    <div data-repeater-list="images">
                        @php $imagesCollection = $item->images ?? collect(); @endphp
                        @forelse($imagesCollection as $img)
                            <div data-repeater-item class="border rounded p-3 mb-4">
                                <div class="row align-items-end">
                                    <input type="hidden" name="id" value="{{ $img->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('dashboard.item_image') }}</label>
                                        <input class="form-control" type="file" name="image">
                                        @if (!empty($img->image_path))
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $img->image_path) }}" alt="image"
                                                    style="max-height:120px" class="img-thumbnail">
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">{{ __('dashboard.item_size') }}</label>
                                        <select name="size" class="form-select size-select">
                                            <option value="{{ $img->size }}" selected>{{ $img->size }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">{{ __('dashboard.item_color') }}</label>
                                        <select name="color" class="form-select color-select">
                                            <option value="{{ $img->color }}" selected>{{ $img->color }}</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3 text-end">
                                        <button type="button" data-repeater-delete
                                            class="btn btn-danger mt-2 col-12">{{ __('dashboard.delete') }}</button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @empty
                            <div data-repeater-item class="border rounded p-3 mb-4">
                                <div class="row align-items-end">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('dashboard.item_image') }}</label>
                                        <input class="form-control" type="file" name="image">
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">{{ __('dashboard.item_size') }}</label>
                                        <select name="size" class="form-select size-select">
                                            <option value="">{{ __('dashboard.choose') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-6 mb-3">
                                        <label class="form-label">{{ __('dashboard.item_color') }}</label>
                                        <select name="color" class="form-select color-select">
                                            <option value="">{{ __('dashboard.choose') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3 text-end">
                                        <button type="button" data-repeater-delete
                                            class="btn btn-danger mt-2 col-12">{{ __('dashboard.delete') }}</button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" data-repeater-create class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> {{ __('dashboard.add_image') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- أزرار الحفظ -->
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">{{ __('dashboard.update_product') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>


@endsection
