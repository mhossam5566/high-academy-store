@foreach ($products as $item)
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
    <div class="col-xxl-5-cols col-xl-3 col-lg-4 col-12 pb-2">
        <div class="modern-product-card position-relative" style="height: 100%;">

            {{-- Debug state value --}}
            @if (config('app.debug'))
                <div
                    style="position: absolute; top: 5px; left: 5px; background: red; color: white; padding: 2px 5px; font-size: 10px; z-index: 10;">
                    State: {{ $item->state }}
                </div>
            @endif

            {{-- Status Ribbon - Redesigned --}}
            @if ($item->state == 0)
                <div class="status-badge unavailable">
                    <i class="fas fa-times-circle"></i> غير متاح
                </div>
            @elseif($item->state == 2)
                <div class="status-badge booking">
                    <i class="fas fa-calendar-check"></i> احجز الآن
                </div>
            @elseif($item->state == 3)
                <div class="status-badge coming-soon">
                    <i class="fas fa-clock"></i> قريباً
                </div>
            @endif

            <a href="{{ route('user.product.show', $item->id) }}" class="text-decoration-none card-link">
                {{-- Product Image Section --}}
                <div class="product-image-wrapper">
                    <img class="product-img lazy" data-src="{{ $item->image_path }}" alt="{{ $item->name }}" />

                    {{-- Offer Badge --}}
                    @if ($item->have_offer == 1)
                        <div class="offer-badge">
                            @if ($item->offer_type == 'percentage')
                                <span class="discount-value">{{ $item->offer_value }}%</span>
                                <span class="discount-label">خصم</span>
                            @else
                                <span class="discount-value">{{ $item->offer_value }}</span>
                                <span class="discount-label">جنيه خصم</span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Product Info Section --}}
                <div class="product-info">
                    <h3 class="product-title">{{ $item->name }}</h3>
                    <p class="product-meta">
                        {{ $item->category ? $item->category->title : '' }}
                        @if ($item->brands)
                            <span class="meta-separator">•</span> {{ $item->brands->title }}
                        @endif
                        @if (isset($item->sliders->title))
                            <span class="meta-separator">•</span> {{ $item->sliders->title }}
                        @endif
                    </p>
                </div>
            </a>

            {{-- Commit Message --}}
            @if ($item->commit != null)
                <div class="commit-message">
                    <i class="fas fa-info-circle"></i> {{ $item->commit }}
                </div>
            @endif

            {{-- WhatsApp Follow Button --}}
            @if ($item->state == 3)
                <div class="whatsapp-follow">
                    <a href="https://www.whatsapp.com/channel/0029VbAlwWH8fewxAkAdCZ23" class="whatsapp-btn"
                        target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-whatsapp"></i> تابعنا على واتساب
                    </a>
                </div>
            @endif

            {{-- Price and Action Section --}}
            <div class="card-footer">
                {{-- Price Section --}}
                <div class="price-section">
                    @if ($item->have_offer == 1)
                        <div class="price-wrapper">
                            <span class="current-price">{{ getOfferPrice($item) }} <small>جنيه</small></span>
                            <span class="old-price">{{ $item->price }}</span>
                        </div>
                    @else
                        <span class="current-price">{{ $item->price }} <small>جنيه</small></span>
                    @endif
                </div>

                {{-- Quantity Controls & Add to Cart --}}
                <div class="action-section">
                    @auth
                        @if ($item->state == 1 && $item->quantity > 0)
                            <div class="quantity-controls compact-controls">
                                <button class="qty-btn minus-btn" onclick="decreaseQuantity({{ $item->id }}, event)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="qty-display" id="quantity{{ $item->id }}">0</span>
                                <button class="qty-btn plus-btn"
                                    onclick="increaseQuantity({{ $item->id }}, event, {{ $item->max_qty_for_order }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button class="add-to-cart-btn add_to_cart" id="add_to_cart{{ $item->id }}"
                                data-quantity="1" data-product-id="{{ $item->id }}">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="btn-text">أضف للسلة</span>
                            </button>
                        @elseif($item->state == 2)
                            <div class="quantity-controls compact-controls">
                                <button class="qty-btn minus-btn" onclick="decreaseQuantity({{ $item->id }}, event)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="qty-display" id="quantity{{ $item->id }}">0</span>
                                <button class="qty-btn plus-btn"
                                    onclick="increaseQuantity({{ $item->id }}, event, {{ $item->max_qty_for_order }})">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button class="add-to-cart-btn booking-btn add_to_cart" id="add_to_cart{{ $item->id }}"
                                data-quantity="1" data-product-id="{{ $item->id }}">
                                <i class="fas fa-calendar-check"></i>
                                <span class="btn-text">احجز الآن</span>
                            </button>
                        @elseif($item->state == 3)
                            <button class="add-to-cart-btn coming-soon-btn" disabled>
                                <i class="fas fa-clock"></i>
                                <span class="btn-text">سيتوفر قريباً</span>
                            </button>
                        @endif
                    @endauth

                    @guest
                        <div class="quantity-controls compact-controls">
                            <button class="qty-btn minus-btn login">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="qty-display" id="quantity{{ $item->id }}">0</span>
                            <button class="qty-btn plus-btn login">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <button class="add-to-cart-btn login">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="btn-text">أضف للسلة</span>
                        </button>
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endforeach

<style>
    /* ============================================
   Modern Product Card Styles - Compact Design
   ============================================ */

    .modern-product-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .modern-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    /* Status Badge - Compact Design */
    .status-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 4px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .status-badge.unavailable {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .status-badge.booking {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-badge.coming-soon {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        color: white;
    }

    /* Product Image - Compact */
    .product-image-wrapper {
        position: relative;
        width: 100%;
        height: 180px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .modern-product-card:hover .product-img {
        transform: scale(1.08);
    }

    .product-img.lazy {
        filter: blur(8px);
        transition: filter 0.3s;
    }

    .product-img.lazy:not([src]) {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Offer Badge - Sleek Design */
    .offer-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 6px 10px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        line-height: 1.2;
        box-shadow: 0 3px 8px rgba(239, 68, 68, 0.4);
        z-index: 2;
    }

    .discount-value {
        font-size: 16px;
        font-weight: 700;
    }

    .discount-label {
        font-size: 9px;
        font-weight: 500;
        opacity: 0.95;
    }

    /* Product Info - Compact */
    .product-info {
        padding: 10px 12px 8px;
    }

    .product-title {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 4px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 40px;
    }

    .product-meta {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .meta-separator {
        margin: 0 4px;
        color: #d1d5db;
    }

    /* Commit Message - Compact */
    .commit-message {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 6px 12px;
        margin: 0 12px 8px;
        border-radius: 6px;
        font-size: 11px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }

    /* WhatsApp Follow - Compact */
    .whatsapp-follow {
        padding: 0 12px 8px;
    }

    .whatsapp-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .whatsapp-btn:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Card Footer - Compact Layout */
    .card-footer {
        padding: 10px 12px 12px;
        border-top: 1px solid #f3f4f6;
        margin-top: auto;
    }

    /* Price Section - Compact */
    .price-section {
        margin-bottom: 8px;
    }

    .price-wrapper {
        display: flex;
        align-items: baseline;
        gap: 8px;
        flex-wrap: wrap;
    }

    .current-price {
        font-size: 18px;
        font-weight: 700;
        color: #e67d15;
    }

    .current-price small {
        font-size: 11px;
        font-weight: 500;
    }

    .old-price {
        font-size: 13px;
        color: #9ca3af;
        text-decoration: line-through;
    }

    /* Action Section - Horizontal Compact Layout */
    .action-section {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Quantity Controls - Extra Compact */
    .compact-controls {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #f9fafb;
        padding: 4px;
        border-radius: 8px;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 11px;
    }

    .minus-btn {
        background: #e5e7eb;
        color: #6b7280;
    }

    .minus-btn:hover {
        background: #d1d5db;
    }

    .plus-btn {
        background: #1c2b30;
        color: white;
    }

    .plus-btn:hover {
        background: #0f1a1e;
    }

    .qty-display {
        min-width: 24px;
        text-align: center;
        font-weight: 600;
        font-size: 13px;
        color: #1f2937;
    }

    /* Add to Cart Button - Compact */
    .add-to-cart-btn {
        flex: 1;
        background: linear-gradient(135deg, #e99239, #e67d15);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .add-to-cart-btn:hover {
        background: linear-gradient(135deg, #e67d15, #d97006);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(233, 146, 57, 0.3);
    }

    .add-to-cart-btn.booking-btn {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .add-to-cart-btn.booking-btn:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
    }

    .add-to-cart-btn.coming-soon-btn {
        background: linear-gradient(135deg, #06b6d4, #0891b2);
        cursor: not-allowed;
        opacity: 0.7;
    }

    .add-to-cart-btn.coming-soon-btn:hover {
        transform: none;
        box-shadow: none;
    }

    .btn-text {
        display: none;
    }

    /* Responsive Design */
    @media (min-width: 576px) {
        .product-image-wrapper {
            height: 200px;
        }

        .product-title {
            font-size: 15px;
        }

        .btn-text {
            display: inline;
        }

        .add-to-cart-btn {
            padding: 10px 14px;
            font-size: 13px;
        }
    }

    @media (min-width: 768px) {
        .product-image-wrapper {
            height: 220px;
        }
    }

    @media (min-width: 1400px) {
        .col-xxl-5-cols {
            flex: 0 0 auto;
            width: 20%;
            max-width: 20%;
        }

        .product-image-wrapper {
            height: 200px;
        }
    }

    /* XXL Columns */
    @media (min-width: 1400px) {
        .col-xxl-1 {
            width: 8.333333%;
        }

        .col-xxl-2 {
            width: 16.666667%;
        }

        .col-xxl-3 {
            width: 25%;
        }

        .col-xxl-4 {
            width: 33.333333%;
        }

        .col-xxl-5 {
            width: 41.666667%;
        }

        .col-xxl-6 {
            width: 50%;
        }

        .col-xxl-7 {
            width: 58.333333%;
        }

        .col-xxl-8 {
            width: 66.666667%;
        }

        .col-xxl-9 {
            width: 75%;
        }

        .col-xxl-10 {
            width: 83.333333%;
        }

        .col-xxl-11 {
            width: 91.666667%;
        }

        .col-xxl-12 {
            width: 100%;
        }
    }

    /* Card Link */
    .card-link {
        color: inherit;
        text-decoration: none;
    }

    .card-link:hover {
        color: inherit;
    }
</style>

<!-- Lazy Loading Script -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const lazyImages = document.querySelectorAll("img.lazy");

        if ("IntersectionObserver" in window) {
            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove("lazy");
                        observer.unobserve(img);
                    }
                });
            });

            lazyImages.forEach(img => observer.observe(img));
        } else {
            lazyImages.forEach(img => {
                img.src = img.dataset.src;
                img.classList.remove("lazy");
            });
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function increaseQuantity(id, event, maxQty) {
        event.stopPropagation();

        let quantityElement = $(`#quantity${id}`);
        let quantity = parseInt(quantityElement.text()) || 0;

        if (maxQty === 0) {
            Swal.fire({
                icon: "error",
                title: "الكمية نفذت",
                text: "عفواً، لقد نفذت الكمية المتاحة للحجز لهذا المنتج.",
                confirmButtonText: "حسناً",
                timer: 3000
            });
            return;
        } else if (quantity >= maxQty) {
            Swal.fire({
                icon: "info",
                title: "تنبيه",
                text: `لا يمكنك طلب أكثر من ${maxQty} من هذا المنتج.`,
                confirmButtonText: "حسناً",
                timer: 3000
            });
            return;
        }

        quantity += 1;
        quantityElement.html(quantity);

        let cartButton = $(`#add_to_cart${id}`);
        cartButton.data("quantity", quantity);
        cartButton.attr("data-quantity", quantity);

        console.log(`✅ المنتج ID: ${id} | الكمية الجديدة: ${quantity} | الحد الأقصى: ${maxQty}`);
    }

    function decreaseQuantity(id, event) {
        event.stopPropagation();
        let quantityElement = $(`#quantity${id}`);
        let quantity = parseInt(quantityElement.text());

        if (quantity > 0) {
            quantity -= 1;
            quantityElement.html(quantity);
            $(`#add_to_cart${id}`).data('quantity', quantity);
        }
    }
</script>
