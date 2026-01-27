@foreach ($mostOrderedProducts as $item)
    @php
        if (!function_exists('getOfferPrice')) {
            function getOfferPrice($item)
            {
                if ($item->have_offer == 1) {
                    if ($item->offer_type == 'percentage') {
                        return $item->price - ($item->price * $item->offer_value) / 100;
                    } else {
                        return $item->price - $item->offer_value;
                    }
                }
            }
        }
    @endphp
    <div class="col-xxl-5-cols col-xl-3 col-lg-4 col-6 pb-3">
        <div class="product-card position-relative h-100 shadow-sm">
            @if ($item->state == 0)
                <div class="ribbon-wrapper">
                    <div class="ribbon bg-danger">غير متاح</div>
                </div>
            @elseif($item->state == 2)
                <div class="ribbon-wrapper">
                    <div class="ribbon bg-warning">احجز الان</div>
                </div>
            @elseif($item->state == 3)
                <div class="ribbon-wrapper">
                    <div class="ribbon bg-info">سيتوفر قريبا</div>
                </div>
            @endif

            <a href="{{ route('user.product.show', $item->id) }}" class="text-decoration-none">
                <div class="product-img-wrapper position-relative overflow-hidden">
                    <img class="img-fluid w-100 lazy product-image" data-src="{{ $item->image_path }}"
                         alt="صوره المنتج {{ $item->name }}"/>
                    <div class="overlay-gradient"></div>
                </div>
                
                <div class="product-content p-3">
                    <h6 class="product-title text-dark fw-bold mb-2 lh-sm">
                        {{ $item->name }}
                    </h6>
                    <p class="product-meta text-muted small mb-0">
                        {{ $item->category ? $item->category->title : '' }}
                        {{ $item->brands ? ' • ' . $item->brands->title : '' }}
                        {{ $item->sliders ? ' • ' . $item->sliders->title : '' }}
                    </p>
                </div>
            </a>

            <div class="product-footer px-3 pb-3">
                @if ($item->commit != null)
                    <div class="alert alert-danger py-2 mb-2 text-center small">
                        {{ $item->commit }}
                    </div>
                @endif
                
                @if($item->state == 3)
                    <div class="text-center mb-2">
                        <a href="https://www.whatsapp.com/channel/0029VbAlwWH8fewxAkAdCZ23"
                           class="btn btn-success btn-sm"
                           target="_blank"
                           rel="noopener noreferrer">
                            <i class="fab fa-whatsapp"></i>
                            تابع معانا
                        </a>
                    </div>
                @endif

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="price-section">
                        @if ($item->have_offer == 1)
                            <div class="d-flex align-items-center gap-2">
                                <span class="price-now fw-bold text-primary fs-5">
                                    {{ getOfferPrice($item) }} <small>EGP</small>
                                </span>
                                <span class="price-old text-muted text-decoration-line-through small">
                                    {{ $item->price }}
                                </span>
                            </div>
                            <small class="text-success">
                                @if ($item->offer_type == 'percentage')
                                    خصم {{ $item->offer_value }}%
                                @else
                                    خصم {{ $item->offer_value }} جنيه
                                @endif
                            </small>
                        @else
                            <span class="price-now fw-bold text-primary fs-5">
                                {{ $item->price }} <small>EGP</small>
                            </span>
                        @endif
                    </div>
                    
                    <div class="quantity-controls">
                        @auth
                            @if (($item->state == 1 && $item->quantity > 0) || $item->state == 2)
                                <div class="d-flex align-items-center gap-1">
                                    <button class="qty-btn qty-minus" onclick="decreaseQuantity({{ $item->id }},event)">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <span class="qty-display" id="quantity{{ $item->id }}">0</span>
                                    <button class="qty-btn qty-plus" onclick="increaseQuantity({{ $item->id }},event, {{$item->max_qty_for_order}})">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            @endif
                        @endauth

                        @guest
                            <div class="d-flex align-items-center gap-1">
                                <button class="qty-btn qty-minus login">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <span class="qty-display" id="quantity{{ $item->id }}">0</span>
                                <button class="qty-btn qty-plus login">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        @endguest
                    </div>
                </div>

                @auth
                    @if ($item->state == 1 && $item->quantity > 0)
                        <a class="add_to_cart btn add-to-cart-btn w-100"
                           id="add_to_cart{{ $item->id }}" 
                           data-quantity="1"
                           data-product-id="{{ $item->id }}">
                            <i class="fas fa-shopping-cart me-1"></i>
                            اضافة للسلة
                        </a>
                    @elseif($item->state == 2)
                        <a class="add_to_cart btn add-to-cart-btn w-100"
                           id="add_to_cart{{ $item->id }}" 
                           data-quantity="1"
                           data-product-id="{{ $item->id }}">
                            <i class="fas fa-bookmark me-1"></i>
                            احجز الان
                        </a>
                    @endif
                @endauth
                
                @guest
                    <button class="btn add-to-cart-btn w-100 login">
                        <i class="fas fa-shopping-cart me-1"></i>
                        اضافة للسلة
                    </button>
                @endguest
            </div>
        </div>
    </div>
@endforeach

<style>
    /* Product Card Styles */
    .product-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.12) !important;
    }

    /* Ribbon Styles */
    .ribbon-wrapper {
        z-index: 3;
        position: absolute;
        top: 0;
        right: 0;
        overflow: hidden;
        width: 70px;
        height: 70px;
    }

    .ribbon {
        font-size: 11px;
        font-weight: 600;
        color: white;
        text-align: center;
        line-height: 18px;
        transform: rotate(45deg);
        position: absolute;
        padding: 5px 0;
        top: 12px;
        right: -22px;
        width: 90px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }

    /* Image Wrapper */
    .product-img-wrapper {
        position: relative;
        aspect-ratio: 1;
        background: #f8f9fa;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .overlay-gradient {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.3), transparent);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .overlay-gradient {
        opacity: 1;
    }

    /* Product Content */
    .product-content {
        min-height: 90px;
    }

    .product-title {
        font-size: 14px;
        height: 40px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-meta {
        font-size: 11px;
        height: 18px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    /* Product Footer */
    .product-footer {
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .price-section {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .price-now {
        font-size: 18px;
        line-height: 1;
    }

    .price-old {
        font-size: 13px;
    }

    /* Quantity Controls */
    .qty-btn {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-minus {
        background: #e9ecef;
        color: #6c757d;
    }

    .qty-plus {
        background: #1c2b30;
        color: white;
    }

    .qty-btn:hover {
        transform: scale(1.1);
    }

    .qty-display {
        min-width: 36px;
        text-align: center;
        font-weight: 600;
        font-size: 16px;
        color: #1c2b30;
    }

    /* Add to Cart Button */
    .add-to-cart-btn {
        background: linear-gradient(135deg, #e99239 0%, #e67d15 100%);
        color: white !important;
        border: none;
        padding: 12px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(233, 146, 57, 0.3);
    }

    .add-to-cart-btn:hover {
        background: linear-gradient(135deg, #e67d15 0%, #d66a00 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(233, 146, 57, 0.4);
    }

    /* Lazy Loading */
    img.lazy {
        filter: blur(10px);
        transition: filter 0.3s;
    }

    img.lazy:not([src]) {
        background-color: #f0f0f0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .product-title {
            font-size: 13px;
            height: 36px;
        }
        
        .price-now {
            font-size: 16px;
        }
        
        .qty-btn {
            width: 28px;
            height: 28px;
            font-size: 10px;
        }
        
        .qty-display {
            min-width: 30px;
            font-size: 14px;
        }
    }

    /* XXL Breakpoint */
    @media (min-width: 1400px) {
        .col-xxl-5-cols {
            flex: 0 0 auto;
            width: 20%;
            max-width: 20%;
        }
    }

    @media (max-width: 600px) {
        .product-image {
            max-height: 242px !important;
        }
    }
</style>

<!-- Lazy Loading Script -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
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

            lazyImages.forEach(img => {
                observer.observe(img);
            });
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

        if (quantity >= maxQty) {
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
    }

    function decreaseQuantity(id, event) {
        event.stopPropagation();
        let quantity = parseInt($(`#quantity${id}`).text());
        if (quantity > 0) {
            quantity -= 1;
            $(`#quantity${id}`).html(quantity);
            $(`#add_to_cart${id}`).data('quantity', quantity);
        }
    }
</script>
