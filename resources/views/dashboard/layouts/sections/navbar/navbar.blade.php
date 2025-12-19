@php
    $containerNav =
        isset($configData['contentLayout']) && $configData['contentLayout'] === 'compact'
            ? 'container-xxl'
            : 'container-fluid';
    $navbarDetached = $navbarDetached ?? '';
@endphp

<!-- Navbar -->
@if (isset($navbarDetached) && $navbarDetached == 'navbar-detached')
    <nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme"
        id="layout-navbar">
@endif
@if (isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <div class="{{ $containerNav }}">
@endif

<!--  Brand demo (display only for navbar-full and hide on below xl) -->
@if (isset($navbarFull))
    <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{ url('/') }}" class="app-brand-link gap-2">
            <span class="app-brand-logo demo">
                @include('_partials.macros', ['height' => 20])
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('variables.templateName') }}</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
            <i class="ti ti-x ti-sm align-middle"></i>
        </a>
    </div>
@endif

<!-- ! Not required for layout-without-menu -->
@if (!isset($navbarHideToggle))
    <div
        class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>
@endif

<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

    @if (!isset($menuHorizontal))
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler d-flex align-items-center px-0" href="javascript:void(0);">
                    <i class="ti ti-search ti-md me-2"></i>
                    <span class="d-none d-md-inline-block text-muted">Search (Ctrl+/)</span>
                </a>
            </div>
        </div>
        <!-- /Search -->
    @endif
    <ul class="navbar-nav flex-row align-items-center ms-auto">
        <!-- Language -->
        <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <i class='ti ti-language rounded-circle ti-md'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                        href="{{ url('lang/en') }}" data-language="en" data-text-direction="ltr">
                        <span class="align-middle">English</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}"
                        href="{{ url('lang/fr') }}" data-language="fr" data-text-direction="ltr">
                        <span class="align-middle">French</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                        href="{{ url('lang/ar') }}" data-language="ar" data-text-direction="rtl">
                        <span class="align-middle">Arabic</span>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item {{ app()->getLocale() === 'de' ? 'active' : '' }}"
                        href="{{ url('lang/de') }}" data-language="de" data-text-direction="ltr">
                        <span class="align-middle">German</span>
                    </a>
                </li>
            </ul>
        </li>
        <!--/ Language -->

        @if (isset($menuHorizontal))
            <!-- Search -->
            <li class="nav-item navbar-search-wrapper me-2 me-xl-0">
                <a class="nav-link search-toggler" href="javascript:void(0);">
                    <i class="ti ti-search ti-md"></i>
                </a>
            </li>
            <!-- /Search -->
        @endif
        @if ($configData['hasCustomizer'] == true)
            <!-- Style Switcher -->
            <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <i class='ti ti-md'></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
                            <span class="align-middle"><i class='ti ti-sun me-2'></i>Light</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
                            <span class="align-middle"><i class="ti ti-moon me-2"></i>Dark</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
                            <span class="align-middle"><i class="ti ti-device-desktop me-2"></i>System</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Style Switcher -->
        @endif

        <!-- Orders Notification (Real-time with Ably) -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" aria-expanded="false">
                <i class="ti ti-shopping-cart ti-md"></i>
                <span class="badge bg-success rounded-pill badge-notifications" id="orders-count"
                    style="display: none;">0</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0" style="max-width: 380px;">
                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">طلبات جديدة</h5>
                        <a href="{{ url('/orders') }}" class="dropdown-notifications-all text-body"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="عرض كل الطلبات">
                            <i class="ti ti-eye fs-4"></i>
                        </a>
                    </div>
                </li>
                <li class="dropdown-notifications-list scrollable-container"
                    style="max-height: 400px; overflow-y: auto;">
                    <ul class="list-group list-group-flush" id="orders-list">
                        <li class="list-group-item text-center text-muted py-4" id="no-orders-msg">
                            <i class="ti ti-shopping-cart ti-lg mb-2 d-block"></i>
                            لا توجد طلبات جديدة
                        </li>
                    </ul>
                </li>
                <li class="dropdown-menu-footer border-top">
                    <a href="{{ url('/orders') }}"
                        class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                        عرض كل الطلبات
                    </a>
                </li>
            </ul>
        </li>
        <!--/ Orders Notification -->

        <!-- General Notification -->
        <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
                data-bs-auto-close="outside" aria-expanded="false">
                <i class="ti ti-bell ti-md"></i>
                <span class="badge bg-danger rounded-pill badge-notifications">5</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end py-0">
                <li class="dropdown-menu-header border-bottom">
                    <div class="dropdown-header d-flex align-items-center py-3">
                        <h5 class="text-body mb-0 me-auto">Notification</h5>
                        <a href="javascript:void(0)" class="dropdown-notifications-all text-body"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Mark all as read"><i
                                class="ti ti-mail-opened fs-4"></i></a>
                    </div>
                </li>
                <li class="dropdown-notifications-list scrollable-container">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar">
                                        <img src="{{ asset('dashboard/assets/img/avatars/1.png') }}" alt
                                            class="h-auto rounded-circle">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                                    <p class="mb-0">Won the monthly best seller gold badge</p>
                                    <small class="text-muted">1h ago</small>
                                </div>
                                <div class="flex-shrink-0 dropdown-notifications-actions">
                                    <a href="javascript:void(0)" class="dropdown-notifications-read"><span
                                            class="badge badge-dot"></span></a>
                                    <a href="javascript:void(0)" class="dropdown-notifications-archive"><span
                                            class="ti ti-x"></span></a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                <li class="dropdown-menu-footer border-top">
                    <a href="javascript:void(0);"
                        class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center">
                        View all notifications
                    </a>
                </li>
            </ul>
        </li>
        <!--/ Notification -->

        <!-- User -->
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <div class="avatar avatar-online">
                    <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('dashboard/assets/img/avatars/1.png') }}"
                        alt class="h-auto rounded-circle">
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item"
                        href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                        <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar avatar-online">
                                    <img src="{{ Auth::user() ? Auth::user()->profile_photo_url : asset('dashboard/assets/img/avatars/1.png') }}"
                                        alt class="h-auto rounded-circle">
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-medium d-block">
                                    @if (Auth::check())
                                        {{ Auth::user()->name }}
                                    @else
                                        John Doe
                                    @endif
                                </span>
                                <small class="text-muted">Admin</small>
                            </div>
                        </div>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                <li>
                    <a class="dropdown-item"
                        href="{{ Route::has('profile.show') ? route('profile.show') : url('pages/profile-user') }}">
                        <i class="ti ti-user-check me-2 ti-sm"></i>
                        <span class="align-middle">My Profile</span>
                    </a>
                </li>
                <li>
                    <div class="dropdown-divider"></div>
                </li>
                @if (Auth::check())
                    <li>
                        <a class="dropdown-item" href=""
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class='ti ti-logout me-2'></i>
                            <span class="align-middle">Logout</span>
                        </a>
                    </li>
                    <form method="POST" id="logout-form" action="">
                        @csrf
                    </form>
                @else
                    <li>
                        <a class="dropdown-item"
                            href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
                            <i class='ti ti-login me-2'></i>
                            <span class="align-middle">Login</span>
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        <!--/ User -->
    </ul>
</div>

<!-- Search Small Screens -->
<div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
    <input type="text"
        class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0"
        placeholder="Search..." aria-label="Search...">
    <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
</div>
@if (isset($navbarDetached) && $navbarDetached == '')
    </div>
@endif
</nav>
<!-- / Navbar -->

<!-- Ably Real-time Script -->
<script src="https://cdn.ably.com/lib/ably.min-1.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Ably
        const ably = new Ably.Realtime('{{ config('broadcasting.connections.ably.key') }}');


        // Connection monitoring
        ably.connection.on('connected', () => {
            console.log('✅ متصل بـ Ably');
        });

        ably.connection.on('disconnected', () => {
            console.log('⚠️ انقطع الاتصال بـ Ably');
        });

        // Subscribe to orders channel
        const ordersChannel = ably.channels.get('dashboard');

        // Listen for new orders
        ordersChannel.subscribe('new-order', (message) => {
            console.log('📨 رسالة كاملة:', message);

            // Parse the data based on Ably's response structure
            let orderData;

            // Check if data is a string that needs parsing
            if (typeof message.data === 'string') {
                try {
                    const parsedData = JSON.parse(message.data);
                    orderData = parsedData.data || parsedData;
                } catch (e) {
                    console.error('خطأ في تحليل البيانات:', e);
                    return;
                }
            } else if (message.data && message.data.data) {
                // If data is already an object with nested data property
                orderData = message.data.data;
            } else {
                orderData = message.data;
            }

            console.log('🛒 طلب جديد:', orderData);

            // Extract user info for customer name
            const customerName = orderData.user ?
                `${orderData.user.first_name} ${orderData.user.last_name}` :
                'عميل';

            // Create order object with required fields
            const order = {
                id: orderData.id,
                customer_name: customerName,
                total: orderData.total,
                created_at: new Date(orderData.created_at).toLocaleString('ar-EG'),
                status: orderData.status,
                phone: orderData.user?.phone || orderData.meta?.phone || 'غير متوفر',
                source: orderData.source || 'market',
                subtotal: orderData.subtotal,
                delivery_fee: orderData.delivery_fee,
                discount: orderData.discount
            };

            playSound();
            updateBadge();
            addOrderToList(order);
            showNotification(order);
            showToast(order);
        });

        // Play notification sound
        function playSound() {
            const audio = new Audio('{{ asset('dashboard/assets/sounds/notification.mp3') }}');
            audio.volume = 0.5;
            audio.play().catch(e => console.log('🔇 الصوت معطل'));
        }

        // Update badge count
        function updateBadge() {
            const badge = document.getElementById('orders-count');
            let count = parseInt(badge.textContent) || 0;
            count++;
            badge.textContent = count;
            badge.style.display = 'inline-block';
            badge.classList.add('pulse-animation');
            setTimeout(() => badge.classList.remove('pulse-animation'), 1000);
        }

        // Add order to dropdown list
        function addOrderToList(order) {
            const list = document.getElementById('orders-list');
            const noMsg = document.getElementById('no-orders-msg');

            if (noMsg) noMsg.remove();

            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action dropdown-notifications-item slide-in';
            li.innerHTML = `
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar">
                            <span class="avatar-initial rounded-circle bg-label-success">
                                <i class="ti ti-shopping-cart"></i>
                            </span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1 small">
                            <span class="badge bg-success">جديد</span> طلب #${order.id}
                        </h6>
                        <p class="mb-0 small">
                            <i class="ti ti-user ti-xs me-1"></i>${order.customer_name}
                        </p>
                        <p class="mb-0 small text-muted">
                            <i class="ti ti-phone ti-xs me-1"></i>${order.phone}
                        </p>
                        <div class="d-flex gap-2 mt-1">
                            <span class="badge bg-label-primary">${order.status}</span>
                            <span class="badge bg-label-info">${order.source}</span>
                        </div>
                        <p class="mb-0 fw-bold text-success mt-1">
                            <i class="ti ti-currency-pound ti-xs"></i>${order.total} جنيه
                        </p>
                        <small class="text-muted"><i class="ti ti-clock ti-xs"></i> ${order.created_at}</small>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="/admin/orders/${order.id}" class="btn btn-sm btn-icon btn-label-success">
                            <i class="ti ti-eye"></i>
                        </a>
                    </div>
                </div>
            `;

            list.insertBefore(li, list.firstChild);

            // Keep only 10 orders
            const items = list.querySelectorAll('.list-group-item');
            if (items.length > 10) items[items.length - 1].remove();
        }

        // Browser notification
        function showNotification(order) {
            if (!("Notification" in window)) return;

            if (Notification.permission === "granted") {
                createNotif(order);
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(perm => {
                    if (perm === "granted") createNotif(order);
                });
            }
        }

        function createNotif(order) {
            const n = new Notification('🛒 طلب جديد!', {
                body: `طلب #${order.id} من ${order.customer_name}\nالمبلغ: ${order.total} جنيه\nالهاتف: ${order.phone}`,
                icon: '{{ asset('dashboard/assets/img/icons/cart.png') }}',
                requireInteraction: false
            });

            n.onclick = () => {
                window.focus();
                window.location.href = '/admin/orders/' + order.id;
                n.close();
            };

            setTimeout(() => n.close(), 5000);
        }

        // Toast notification
        function showToast(order) {
            const toast = document.createElement('div');
            toast.className = 'custom-toast';
            toast.innerHTML = `
                <div class="toast-icon">🛒</div>
                <div class="toast-content">
                    <strong>طلب جديد #${order.id}</strong>
                    <p class="mb-1"><i class="ti ti-user ti-xs"></i> ${order.customer_name}</p>
                    <p class="mb-1"><i class="ti ti-phone ti-xs"></i> ${order.phone}</p>
                    <p class="mb-0 fw-bold text-success"><i class="ti ti-currency-pound ti-xs"></i> ${order.total} جنيه</p>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            `;

            document.body.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Clear badge on dropdown open
        document.querySelector('[href="javascript:void(0);"][data-bs-toggle="dropdown"]')?.addEventListener(
            'click', () => {
                setTimeout(() => {
                    const badge = document.getElementById('orders-count');
                    badge.textContent = '0';
                    badge.style.display = 'none';
                }, 500);
            });

        // Request notification permission
        if ("Notification" in window && Notification.permission === "default") {
            Notification.requestPermission();
        }
    });
</script>

<style>
    .pulse-animation {
        animation: pulse 0.5s ease-in-out;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.3);
        }
    }

    .slide-in {
        animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .dropdown-notifications-item {
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }

    .dropdown-notifications-item:hover {
        background: rgba(40, 199, 111, 0.08);
        border-left-color: #28c76f;
        transform: translateX(5px);
    }

    .custom-toast {
        position: fixed;
        top: 20px;
        right: -400px;
        min-width: 350px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        padding: 16px;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: right 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    .custom-toast.show {
        right: 20px;
    }

    .toast-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #28c76f, #1f9d57);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-content {
        flex-grow: 1;
    }

    .toast-content strong {
        display: block;
        margin-bottom: 4px;
        color: #333;
    }

    .toast-content p {
        margin: 0;
        font-size: 13px;
        color: #666;
    }

    .toast-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        line-height: 1;
    }

    .toast-close:hover {
        color: #333;
    }

    .scrollable-container::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .scrollable-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }
</style>
