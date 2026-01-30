@extends('user.layouts.master')

@section('title')
    {{ $product->name }}
@endsection

<?php
$sliders = $product->sliders->title ?? 'غير محدد';
$category = $product->category->title ?? 'غير محدد';
$brands = $product->brands->title ?? 'غير محدد';
?>

@section('keywords', 'اسم الكتاب: ' . $product->name . ' ' . 'الصف الدراسي: ' . $sliders . ' ' . 'اسم المادة: ' .
    $category . ' ' . 'كتاب ' . $brands . ' ' . 'السعر: ' . $product->price)

@section('book-image', $product->image_path)

@php
    if (!function_exists('getOfferPrice')) {
        function getOfferPrice($product)
        {
            if ($product->have_offer == 1) {
                if ($product->offer_type == 'percentage') {
                    return $product->price - ($product->price * $product->offer_value) / 100;
                } else {
                    return $product->price - $product->offer_value;
                }
            }
        }
    }
@endphp

@section('content')
    <style>
        /* ============================================
           Modern Product Detail Page Styles
           ============================================ */

        .product-detail-container {
            padding: 40px 0;
            background: #f8f9fa;
        }

        .product-detail-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 30px;
        }

        /* Image Gallery Section */
        .product-gallery {
            position: relative;
            background: #fff;
            padding: 20px;
        }

        .main-image-container {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f9fa;
            margin-bottom: 20px;
        }

        .main-product-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            display: block;
        }

        /* Status Badges on Image */
        .image-badges {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .detail-status-badge {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .detail-status-badge.unavailable {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .detail-status-badge.booking {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .detail-status-badge.coming-soon {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .detail-status-badge.available {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        /* Offer Badge on Image */
        .detail-offer-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 12px 16px;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.2;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
            z-index: 10;
        }

        .detail-offer-badge .discount-value {
            font-size: 24px;
            font-weight: 700;
        }

        .detail-offer-badge .discount-label {
            font-size: 11px;
            font-weight: 500;
            opacity: 0.95;
        }

        /* Thumbnails */
        .carousel-thumbnails {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .carousel-thumbnails img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .carousel-thumbnails img:hover {
            border-color: #e67d15;
            transform: scale(1.05);
        }

        .carousel-thumbnails img.active {
            border-color: #e67d15;
            box-shadow: 0 4px 12px rgba(230, 125, 21, 0.3);
        }

        /* Product Info Section */
        .product-info-section {
            padding: 30px;
        }

        .product-detail-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 20px;
            line-height: 1.3;
        }

        /* Price Section */
        .price-display {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .price-label {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .price-values {
            display: flex;
            align-items: baseline;
            gap: 15px;
            flex-wrap: wrap;
        }

        .current-price-large {
            font-size: 32px;
            font-weight: 700;
            color: #e67d15;
        }

        .old-price-large {
            font-size: 24px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .price-currency {
            font-size: 16px;
            font-weight: 500;
            color: #6b7280;
        }

        /* Commit Message */
        .detail-commit-message {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* WhatsApp Follow */
        .detail-whatsapp-follow {
            margin-bottom: 20px;
        }

        .detail-whatsapp-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .detail-whatsapp-btn:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        /* Product Details */
        .product-details-list {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .detail-item {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #4b5563;
            min-width: 120px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 500;
        }

        /* Description */
        .product-description {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .description-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            display: flex;
            align-items: t;
            gap: 8px;
        }

        .description-text {
            color: #4b5563;
            line-height: 1.8;
            font-size: 15px;
        }

        /* Color and Size Options */
        .options-section {
            margin-bottom: 25px;
        }

        .option-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .color-option,
        .size-option {
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            margin: 0 8px 8px 0;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            background: white;
        }

        .color-option:hover,
        .size-option:hover {
            border-color: #e67d15;
            transform: translateY(-2px);
        }

        .color-option.active {
            border-color: #e67d15;
            background: linear-gradient(135deg, #e99239, #e67d15);
            color: white;
        }

        .size-option.active {
            border-color: #e67d15;
            background: linear-gradient(135deg, #e99239, #e67d15);
            color: white;
        }

        .color-option input,
        .size-option input {
            display: none;
        }

        /* Stock Warning */
        .stock-warning {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-left: 4px solid #f59e0b;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .stock-warning-text {
            color: #92400e;
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Add to Cart Section */
        .add-to-cart-section {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
        }

        .quantity-input-wrapper {
            margin-bottom: 15px;
        }

        .quantity-label {
            font-size: 14px;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .quantity-input {
            width: 100%;
            max-width: 200px;
            padding: 12px 20px;
            border: 2px solid #e67d15;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
        }

        .quantity-input:focus {
            outline: none;
            border-color: #d97006;
            box-shadow: 0 0 0 3px rgba(230, 125, 21, 0.1);
        }

        .add-to-cart-btn-large {
            width: 100%;
            background: linear-gradient(135deg, #e99239, #e67d15);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(233, 146, 57, 0.3);
        }

        .add-to-cart-btn-large:hover {
            background: linear-gradient(135deg, #e67d15, #d97006);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(233, 146, 57, 0.4);
        }

        .add-to-cart-btn-large.booking-style {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .add-to-cart-btn-large.booking-style:hover {
            background: linear-gradient(135deg, #d97706, #b45309);
        }

        .login-btn-large {
            width: 100%;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 16px 30px;
            border-radius: 12px;
            font-size: 18px;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .login-btn-large:hover {
            background: linear-gradient(135deg, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
            color: white;
        }

        /* Unavailable Message */
        .unavailable-message {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border-left: 4px solid #ef4444;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .unavailable-text {
            color: #991b1b;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Responsive Design */
        @media (max-width: 991px) {
            .main-product-image {
                height: 400px;
            }

            .product-detail-title {
                font-size: 24px;
            }

            .current-price-large {
                font-size: 28px;
            }

            .product-info-section {
                padding: 20px;
            }
        }

        @media (max-width: 576px) {
            .main-product-image {
                height: 300px;
            }

            .product-detail-title {
                font-size: 20px;
            }

            .current-price-large {
                font-size: 24px;
            }

            .carousel-thumbnails img {
                width: 60px;
                height: 60px;
            }

            .product-info-section {
                padding: 15px;
            }

            .add-to-cart-section {
                padding: 15px;
            }
        }
    </style>

    <!-- Product Detail Start -->
    <section class="product-detail-container" dir="rtl">
        <div class="container">
            <div class="row">
                <!-- Image Gallery Column -->
                <div class="col-lg-5 mb-4">
                    <div class="product-detail-card">
                        <div class="product-gallery">
                            <!-- Main Image Container -->
                            <div class="main-image-container">
                                <!-- Status Badges -->
                                <div class="image-badges">
                                    @if ($product->state == 0)
                                        <div class="detail-status-badge unavailable">
                                            <i class="fas fa-times-circle"></i> غير متاح
                                        </div>
                                    @elseif($product->state == 2)
                                        <div class="detail-status-badge booking">
                                            <i class="fas fa-calendar-check"></i> احجز الآن
                                        </div>
                                    @elseif($product->state == 3)
                                        <div class="detail-status-badge coming-soon">
                                            <i class="fas fa-clock"></i> سيتوفر قريباً
                                        </div>
                                    @else
                                        <div class="detail-status-badge available">
                                            <i class="fas fa-check-circle"></i> متاح
                                        </div>
                                    @endif
                                </div>

                                <!-- Offer Badge -->
                                @if ($product->have_offer == 1)
                                    <div class="detail-offer-badge">
                                        @if ($product->offer_type == 'percentage')
                                            <span class="discount-value">{{ $product->offer_value }}%</span>
                                            <span class="discount-label">خصم</span>
                                        @else
                                            <span class="discount-value">{{ $product->offer_value }}</span>
                                            <span class="discount-label">جنيه خصم</span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Carousel -->
                                <div id="product-carousel" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <div class="carousel-item active">
                                            <img class="main-product-image" src="{{ $product->image_path }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                        @foreach ($product->images as $image)
                                            <div class="carousel-item">
                                                <img class="main-product-image"
                                                    src="{{ asset('storage/' . $image->image_path) }}" alt="صورة المنتج">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Thumbnails -->
                            <div class="carousel-thumbnails">
                                <img src="{{ $product->image_path }}" data-bs-target="#product-carousel"
                                    data-bs-slide-to="0" class="active" alt="صورة مصغرة">
                                @foreach ($product->images as $index => $image)
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        data-bs-target="#product-carousel" data-bs-slide-to="{{ $index + 1 }}"
                                        alt="صورة مصغرة {{ $index + 1 }}">
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Info Column -->
                <div class="col-lg-7">
                    <div class="product-detail-card">
                        <div class="product-info-section">
                            <!-- Product Title -->
                            <h1 class="product-detail-title">{{ $product->name }}</h1>

                            <!-- Commit Message -->
                            @if ($product->commit != null)
                                <div class="detail-commit-message">
                                    <i class="fas fa-info-circle"></i>
                                    <span>{{ $product->commit }}</span>
                                </div>
                            @endif

                            <!-- WhatsApp Follow -->
                            @if ($product->state == 3)
                                <div class="detail-whatsapp-follow">
                                    <a href="https://www.whatsapp.com/channel/0029VbAlwWH8fewxAkAdCZ23"
                                        class="detail-whatsapp-btn" target="_blank" rel="noopener noreferrer">
                                        <i class="fab fa-whatsapp fa-lg"></i>
                                        <span>تابعنا على واتساب للحصول على التحديثات</span>
                                    </a>
                                </div>
                            @endif

                            <!-- Price Display -->
                            <div class="price-display">
                                <div class="price-label">
                                    <i class="fas fa-tag"></i>
                                    <span>السعر</span>
                                </div>
                                <div class="price-values">
                                    @if ($product->have_offer == 1)
                                        <span class="current-price-large">{{ getOfferPrice($product) }}</span>
                                        <span class="old-price-large">{{ $product->price }}</span>
                                        <span class="price-currency">جنيه</span>
                                    @else
                                        <span class="current-price-large">{{ $product->price }}</span>
                                        <span class="price-currency">جنيه</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Description -->
                            @if ($product->description)
                                <div class="product-description">
                                    <h3 class="description-title">
                                        <i class="fas fa-align-right"></i>
                                        <span>الوصف</span>
                                    </h3>
                                    <p class="description-text">{{ $product->description }}</p>
                                </div>
                            @endif

                            <!-- Product Details -->
                            <div class="product-details-list">
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span>اسم المدرس:</span>
                                    </div>
                                    <div class="detail-value">{{ $product->brands->title ?? 'غير محدد' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-book"></i>
                                        <span>اسم المادة:</span>
                                    </div>
                                    <div class="detail-value">{{ $product->category->title ?? 'غير محدد' }}</div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-label">
                                        <i class="fas fa-graduation-cap"></i>
                                        <span>الصف الدراسي:</span>
                                    </div>
                                    <div class="detail-value">{{ $product->sliders->title ?? 'غير محدد' }}</div>
                                </div>
                            </div>

                            <!-- Color Options -->
                            @if (!empty($product->colors))
                                <div class="options-section">
                                    <h3 class="option-title">
                                        <i class="fas fa-palette"></i>
                                        <span>الألوان المتاحة</span>
                                    </h3>
                                    <div id="colorChoices">
                                        @foreach ($product->colors as $color)
                                            <label class="color-option">
                                                <input type="radio" name="selected_color{{ $product->id }}"
                                                    value="{{ $color }}">
                                                {{ $color }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Size Options -->
                            @if (!empty($product->sizes))
                                <div class="options-section">
                                    <h3 class="option-title">
                                        <i class="fas fa-ruler"></i>
                                        <span>الأحجام المتاحة</span>
                                    </h3>
                                    <div id="sizeChoices">
                                        @foreach ($product->sizes as $size)
                                            <label class="size-option">
                                                <input type="radio" name="selected_size{{ $product->id }}"
                                                    value="{{ $size }}">
                                                {{ $size }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Add to Cart Section -->
                            @if ($product->state == 0 || $product->quantity == 0)
                                <div class="unavailable-message">
                                    <p class="unavailable-text">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <span>عذراً، المنتج غير متوفر حالياً</span>
                                    </p>
                                </div>
                            @else
                                @auth
                                    <!-- Stock Warning -->
                                    @if ($product->max_qty_for_order <= 10)
                                        <div class="stock-warning">
                                            <p class="stock-warning-text">
                                                <i class="fas fa-exclamation-circle"></i>
                                                <span>⚠️ باقي {{ $product->max_qty_for_order }} وحدات فقط في المخزون</span>
                                            </p>
                                        </div>
                                    @endif

                                    <div class="add-to-cart-section">
                                        <!-- Quantity Input -->
                                        <div class="quantity-input-wrapper">
                                            <label class="quantity-label">
                                                <i class="fas fa-shopping-basket"></i>
                                                <span>الكمية المطلوبة</span>
                                            </label>
                                            <input type="number" name="quantity" id="quantity{{ $product->id }}"
                                                data-max-quantity="{{ $product->max_qty_for_order }}"
                                                placeholder="أدخل الكمية" max="{{ $product->max_qty_for_order }}"
                                                class="quantity-input" dir="rtl" min="1" value="1">
                                        </div>

                                        <!-- Add to Cart Button -->
                                        @if ($product->state == 2)
                                            <button class="add-to-cart-btn-large booking-style add_to_cart"
                                                id="add_to_cart{{ $product->id }}" data-quantity="1"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-calendar-check"></i>
                                                <span>احجز الآن</span>
                                            </button>
                                        @else
                                            <button class="add-to-cart-btn-large add_to_cart"
                                                id="add_to_cart{{ $product->id }}" data-quantity="1"
                                                data-product-id="{{ $product->id }}">
                                                <i class="fas fa-shopping-cart"></i>
                                                <span>إضافة إلى السلة</span>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <!-- Login Button -->
                                    <a href="{{ route('user.login.user') }}" class="login-btn-large">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>سجل دخول لإضافة المنتج</span>
                                    </a>
                                @endauth
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Detail End -->

    <script>
        // Carousel Thumbnails
        document.querySelectorAll('.carousel-thumbnails img').forEach(function(thumbnail, index) {
            thumbnail.addEventListener('click', function() {
                document.querySelector('.carousel-thumbnails img.active').classList.remove('active');
                this.classList.add('active');
            });
        });

        // Update active thumbnail when carousel slide changes
        const product_carousel = document.querySelector('#product-carousel');
        if (product_carousel) {
            product_carousel.addEventListener('slid.bs.carousel', function(e) {
                const active_index = e.to;
                document.querySelector('.carousel-thumbnails img.active').classList.remove('active');
                document.querySelector(`.carousel-thumbnails img[data-bs-slide-to="${active_index}"]`).classList
                    .add('active');
            });
        }

        // Update quantity data
        setInterval(function() {
            $('#quantity{{ $product->id }}').on('change', function() {
                $('#add_to_cart{{ $product->id }}').data('quantity', $(this).val());
            });
        }, 1000);

        // Color selection
        document.querySelectorAll('#colorChoices .color-option input').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('#colorChoices .color-option').forEach(label => {
                    label.classList.remove('active');
                });
                this.closest('.color-option').classList.add('active');
            });
        });

        // Size selection
        document.querySelectorAll('#sizeChoices .size-option input').forEach(input => {
            input.addEventListener('change', function() {
                document.querySelectorAll('#sizeChoices .size-option').forEach(label => {
                    label.classList.remove('active');
                });
                this.closest('.size-option').classList.add('active');
            });
        });

        // Quantity validation
        $(`input[name="quantity"]`).on('input', function() {
            var maxQty = $(this).data('max-quantity');
            var currentVal = parseInt($(this).val());

            if (currentVal > maxQty) {
                Swal.fire({
                    icon: "info",
                    title: "تنبيه",
                    text: `لا يمكنك طلب أكثر من ${maxQty} من هذا المنتج.`,
                    confirmButtonText: "حسناً",
                    timer: 3000
                });
                $(this).val(maxQty);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
