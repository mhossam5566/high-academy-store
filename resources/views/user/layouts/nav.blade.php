<style>
    :root {
        --nav-bg: #1a1a2e;
        --nav-accent: #e07b39;
        --nav-accent-light: #f59a5a;
        --nav-surface: rgba(255, 255, 255, 0.06);
        --nav-border: rgba(255, 255, 255, 0.10);
        --nav-text: rgba(255, 255, 255, 0.88);
        --nav-muted: rgba(255, 255, 255, 0.50);
        --nav-radius: 12px;
        --nav-height: 64px;
    }

    /* ═══ NAVBAR ═══ */
    .main-navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: var(--nav-height);
        background: var(--nav-bg);
        display: flex;
        align-items: center;
        padding: 0 20px;
        z-index: 1050;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.25);
        font-family: 'Cairo', sans-serif;
        direction: rtl;
    }

    .nav-inner {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 12px;
        direction: ltr;
    }

    /* Flip logo order so Academy (orange) appears first in RTL */
    .nav-logo {
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
        text-decoration: none;
        flex-shrink: 0;
    }

    .nav-logo .logo-high {
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        background: #fff;
        color: var(--nav-bg);
        padding: 4px 10px;
        border-radius: 0 6px 6px 0;
    }

    .nav-logo .logo-academy {
        font-size: 15px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        background: var(--nav-accent);
        color: #fff;
        padding: 4px 10px;
        border-radius: 6px 0 0 6px;
    }

    /* Search bar (desktop) */
    .nav-search {
        flex: 1;
        max-width: 380px;
        margin-right: auto;
        margin-left: auto;
        align-self: center;
    }

    .nav-search form {
        display: flex;
        align-items: center;
        background: var(--nav-surface);
        border: 1px solid var(--nav-border);
        border-radius: 10px;
        overflow: hidden;
        transition: border-color .2s, box-shadow .2s;
    }

    .nav-search form:focus-within {
        border-color: var(--nav-accent);
        box-shadow: 0 0 0 3px rgba(224, 123, 57, .18);
    }

    .nav-search input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        padding: 8px 14px;
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        color: #fff;
        direction: rtl;
    }

    .nav-search input::placeholder {
        color: var(--nav-muted);
    }

    .nav-search button {
        background: var(--nav-accent);
        border: none;
        padding: 8px 14px;
        color: #fff;
        cursor: pointer;
        font-size: 13px;
        transition: background .2s;
        display: flex;
        align-items: center;
    }

    .nav-search button:hover {
        background: var(--nav-accent-light);
    }

    /* Right side actions */
    .nav-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-right: auto;
        margin-left: 0;
        flex-shrink: 0;
    }

    /* Cart icon */
    .nav-cart-btn {
        position: relative;
        background: var(--nav-surface);
        border: 1px solid var(--nav-border);
        border-radius: var(--nav-radius);
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: var(--nav-accent);
        font-size: 17px;
        transition: background .2s, border-color .2s;
        cursor: pointer;
    }

    .nav-cart-btn:hover {
        background: rgba(224, 123, 57, .15);
        border-color: var(--nav-accent);
        color: var(--nav-accent);
    }

    .nav-cart-badge {
        position: absolute;
        top: -5px;
        left: -5px;
        background: var(--nav-accent);
        color: #fff;
        font-size: 10px;
        font-weight: 800;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Cairo', sans-serif;
    }

    /* Hamburger */
    .nav-toggle-btn {
        background: var(--nav-surface);
        border: 1px solid var(--nav-border);
        border-radius: var(--nav-radius);
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--nav-text);
        font-size: 16px;
        cursor: pointer;
        transition: background .2s, border-color .2s;
    }

    .nav-toggle-btn:hover {
        background: rgba(255, 255, 255, .12);
        border-color: rgba(255, 255, 255, .25);
    }

    /* Mobile search row */
    .nav-search-mobile {
        display: none;
        background: var(--nav-bg);
        padding: 10px 16px;
        border-top: 1px solid var(--nav-border);
        position: fixed;
        top: var(--nav-height);
        left: 0;
        right: 0;
        z-index: 1049;
    }

    .nav-search-mobile form {
        display: flex;
        align-items: center;
        background: var(--nav-surface);
        border: 1px solid var(--nav-border);
        border-radius: 10px;
        overflow: hidden;
    }

    .nav-search-mobile input {
        flex: 1;
        background: none;
        border: none;
        outline: none;
        padding: 9px 14px;
        font-family: 'Cairo', sans-serif;
        font-size: 13px;
        color: #fff;
        direction: rtl;
    }

    .nav-search-mobile input::placeholder {
        color: var(--nav-muted);
    }

    .nav-search-mobile button {
        background: var(--nav-accent);
        border: none;
        padding: 9px 16px;
        color: #fff;
        cursor: pointer;
        font-size: 13px;
        display: flex;
        align-items: center;
    }

    /* ═══ SIDEBAR ═══ */
    .app-sidebar {
        position: fixed;
        top: 0;
        right: -320px;
        width: 300px;
        bottom: 0;
        background: var(--nav-bg);
        border-left: 1px solid var(--nav-border);
        z-index: 1100;
        overflow-y: auto;
        transition: right .3s cubic-bezier(.4, 0, .2, 1);
        display: flex;
        flex-direction: column;
        font-family: 'Cairo', sans-serif;
    }

    .app-sidebar.show {
        right: 0;
    }

    /* Sidebar overlay */
    .sidebar-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        z-index: 1099;
        opacity: 0;
        pointer-events: none;
        transition: opacity .3s;
    }

    .sidebar-overlay.show {
        opacity: 1;
        pointer-events: all;
    }

    /* Sidebar header */
    .sidebar-head {
        padding: 18px 20px 16px;
        border-bottom: 1px solid var(--nav-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar-logo {
        display: flex;
        flex-direction: row-reverse;
        align-items: center;
    }

    .sidebar-logo .logo-high {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        background: #fff;
        color: var(--nav-bg);
        padding: 3px 8px;
        border-radius: 0 5px 5px 0;
    }

    .sidebar-logo .logo-academy {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        background: var(--nav-accent);
        color: #fff;
        padding: 3px 8px;
        border-radius: 5px 0 0 5px;
    }

    .sidebar-close {
        background: rgba(255, 255, 255, .1);
        border: 1px solid var(--nav-border);
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--nav-text);
        font-size: 14px;
        transition: background .2s;
    }

    .sidebar-close:hover {
        background: rgba(255, 255, 255, .2);
    }

    /* Sidebar user profile */
    .sidebar-profile {
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        border-bottom: 1px solid var(--nav-border);
        text-decoration: none;
        transition: background .2s;
    }

    .sidebar-profile:hover {
        background: var(--nav-surface);
    }

    .sidebar-avatar {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--nav-accent);
        flex-shrink: 0;
    }

    .sidebar-profile-name {
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        display: block;
    }

    .sidebar-profile-sub {
        color: var(--nav-muted);
        font-size: 12px;
    }

    /* Sidebar nav items */
    .sidebar-nav {
        list-style: none;
        padding: 12px 12px;
        margin: 0;
        flex: 1;
    }

    .sidebar-nav li+li {
        margin-top: 2px;
    }

    .sidebar-nav .nav-divider {
        height: 1px;
        background: var(--nav-border);
        margin: 8px 0;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 14px;
        border-radius: 10px;
        color: var(--nav-text);
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: background .18s, color .18s;
        cursor: pointer;
        direction: rtl;
        border: none;
        background: none;
        width: 100%;
        text-align: right;
    }

    .sidebar-nav-link:hover {
        background: var(--nav-surface);
        color: #fff;
    }

    .sidebar-nav-link.active {
        background: rgba(224, 123, 57, .18);
        color: var(--nav-accent);
    }

    .sidebar-nav-link .nav-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: var(--nav-surface);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        transition: background .18s;
    }

    .sidebar-nav-link:hover .nav-icon {
        background: rgba(255, 255, 255, .12);
    }

    .sidebar-nav-link.active .nav-icon {
        background: rgba(224, 123, 57, .25);
    }

    /* Dropdown inside sidebar */
    .sidebar-dropdown {
        display: none;
        padding: 4px 0 4px 14px;
    }

    .sidebar-dropdown.open {
        display: block;
    }

    .sidebar-dropdown-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        border-radius: 8px;
        color: var(--nav-text);
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        transition: background .15s, color .15s;
        direction: rtl;
    }

    .sidebar-dropdown-link:hover {
        background: var(--nav-surface);
        color: #fff;
    }

    /* Logout / Danger */
    .sidebar-nav-link.danger {
        color: #fc8181;
    }

    .sidebar-nav-link.danger:hover {
        background: rgba(220, 38, 38, .15);
        color: #fc8181;
    }

    .sidebar-nav-link.danger .nav-icon {
        background: rgba(220, 38, 38, .15);
    }

    /* ═══ Responsive ═══ */
    @media (max-width: 600px) {

        .nav-logo .logo-high,
        .nav-logo .logo-academy {
            font-size: 12px;
            padding: 3px 7px;
        }

        .nav-search {
            display: none;
        }

        .nav-search-mobile {
            display: block;
        }

        .main-navbar {
            padding: 0 12px;
        }

        .app-sidebar {
            width: 270px;
            right: -270px;
        }
    }

    @media (min-width: 601px) {
        .nav-search-mobile {
            display: none !important;
        }
    }
</style>

@php
    $lastSegment = request()->segment(count(request()->segments()));
@endphp

{{-- ═══ NAVBAR ═══ --}}
<nav class="main-navbar">
    <div class="nav-inner">

        {{-- Logo --}}
        <a class="nav-logo" href="/">
            <span class="logo-high">High</span>
            <span class="logo-academy">Academy Store</span>
        </a>

        {{-- Search (desktop, shows only on shop page) --}}
        @if ($lastSegment == 'ar')
            <div class="nav-search">
                <form id="searchId" action="{{ route('user.shop') }}" method="GET">
                    <input name="title" type="text" placeholder="ابحث عن اسم الكتاب"
                        value="{{ request('title') }}">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
        @endif

        {{-- Actions --}}
        <div class="nav-actions">
            <a href="{{ route('user.card') }}" class="nav-cart-btn">
                <i class="fas fa-shopping-bag"></i>
                <span class="nav-cart-badge">{{ Cart::instance('shopping')->count() }}</span>
            </a>
            <button class="nav-toggle-btn" id="sidebarToggle" aria-label="القائمة">
                <i class="fas fa-bars"></i>
            </button>
        </div>

    </div>
</nav>

{{-- Mobile search strip --}}
@if ($lastSegment == 'ar')
    <div class="nav-search-mobile" id="mobileSearch">
        <form action="{{ route('user.shop') }}" method="GET">
            <input name="title" type="text" placeholder="ابحث عن اسم الكتاب" value="{{ request('title') }}">
            <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
@endif

{{-- Overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay"></div>

{{-- ═══ SIDEBAR ═══ --}}
<div class="app-sidebar" id="sidebar">

    {{-- Head --}}
    <div class="sidebar-head">
        <div class="sidebar-logo">
            <span class="logo-high">High</span>
            <span class="logo-academy">Academy</span>
        </div>
        <button class="sidebar-close" id="closeSidebar" aria-label="إغلاق">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Profile --}}
    @auth
        <a href="{{ route('user.myaccount') }}" class="sidebar-profile">
            <img src="{{ Auth()->user()->profile_image ? asset('storage/images/user/' . Auth()->user()->profile_image) : asset('storage/images/pngegg.png') }}"
                alt="صورة المستخدم" class="sidebar-avatar">
            <div>
                <span class="sidebar-profile-name">{{ Auth()->user()->name }}</span>
                <span class="sidebar-profile-sub">عرض حسابي</span>
            </div>
        </a>
    @endauth

    @guest
        <div class="sidebar-profile" style="cursor:default;">
            <img src="{{ asset('storage/images/pngegg.png') }}" alt="Logo" class="sidebar-avatar">
            <div>
                <span class="sidebar-profile-name">زائر</span>
                <span class="sidebar-profile-sub">سجل دخولك للمزيد</span>
            </div>
        </div>
    @endguest

    {{-- Nav --}}
    <ul class="sidebar-nav">

        <li>
            <a href="{{ route('user.home') }}"
                class="sidebar-nav-link {{ $lastSegment == '' || $lastSegment == 'ar' ? '' : '' }}">
                <span class="nav-icon">🏠</span>
                الرئيسية
            </a>
        </li>

        <li>
            <div class="nav-divider"></div>
        </li>

        <li>
            <a href="{{ route('user.shop') }}" class="sidebar-nav-link">
                <span class="nav-icon">🛒</span>
                المتجر
            </a>
        </li>

        <li>
            <div class="nav-divider"></div>
        </li>

        <li>
            <a href="{{ route('user.orders.user') }}"
                class="sidebar-nav-link {{ $lastSegment == 'myorders' ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                طلباتي
            </a>
        </li>

        @auth
            <li>
                <div class="nav-divider"></div>
            </li>

            <li>
                <a href="{{ route('user.vochers.user') }}"
                    class="sidebar-nav-link {{ $lastSegment == 'myvouchers' ? 'active' : '' }}">
                    <span class="nav-icon">🎁</span>
                    أكوادي
                </a>
            </li>

            <li>
                <div class="nav-divider"></div>
            </li>

            <li>
                <a href="{{ route('user.shipping') }}"
                    class="sidebar-nav-link {{ $lastSegment == 'shipping' ? 'active' : '' }}">
                    <span class="nav-icon">🚚</span>
                    عناوين استلام شحنتك
                </a>
            </li>
        @endauth

        <li>
            <div class="nav-divider"></div>
        </li>

        <li>
            <a href="{{ route('user.fqa') }}" class="sidebar-nav-link">
                <span class="nav-icon">❓</span>
                الأسئلة الشائعة
            </a>
        </li>

        <li>
            <div class="nav-divider"></div>
        </li>

        {{-- Contact dropdown --}}
        <li>
            <button class="sidebar-nav-link" onclick="toggleContactDropdown()" id="contactToggle">
                <span class="nav-icon">✉️</span>
                تواصل معنا
                <i class="fas fa-chevron-left" id="contactChevron"
                    style="margin-right:auto; margin-left:0; font-size:11px; transition:transform .2s; color:var(--nav-muted);"></i>
            </button>
            <div class="sidebar-dropdown" id="contactDropdown">
                <a href="https://www.facebook.com/highacademy2" target="_blank" class="sidebar-dropdown-link">
                    <span style="color:#1877f2; font-size:15px;"><i class="fab fa-facebook-f"></i></span>
                    فيسبوك
                </a>
                <a href="https://wa.me/201550234324" target="_blank" class="sidebar-dropdown-link">
                    <span style="color:#25d366; font-size:15px;"><i class="fab fa-whatsapp"></i></span>
                    واتساب
                </a>
                <a href="https://www.whatsapp.com/channel/0029VbAlwWH8fewxAkAdCZ23" target="_blank"
                    class="sidebar-dropdown-link">
                    <span style="color:#25d366; font-size:15px;"><i class="fab fa-whatsapp"></i></span>
                    قناة واتساب
                </a>
            </div>
        </li>

        <li>
            <div class="nav-divider"></div>
        </li>

        @auth
            <li>
                <a href="{{ route('user.logout') }}" class="sidebar-nav-link danger">
                    <span class="nav-icon">🚪</span>
                    تسجيل خروج
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('user.login.user') }}" class="sidebar-nav-link">
                    <span class="nav-icon">🔑</span>
                    تسجيل دخول
                </a>
            </li>
        @endauth

    </ul>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const closeSidebarBtn = document.getElementById('closeSidebar');

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebarFn() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    sidebarToggle.addEventListener('click', e => {
        sidebar.classList.contains('show') ? closeSidebarFn() : openSidebar();
        e.stopPropagation();
    });
    closeSidebarBtn.addEventListener('click', closeSidebarFn);
    overlay.addEventListener('click', closeSidebarFn);

    function toggleContactDropdown() {
        const dd = document.getElementById('contactDropdown');
        const ch = document.getElementById('contactChevron');
        const open = dd.classList.toggle('open');
        ch.style.transform = open ? 'rotate(-90deg)' : 'rotate(0)';
    }

    // Hide mobile search on scroll (only on shop page)
    $(document).ready(function() {
        var mobileSearch = $('#mobileSearch');
        if (mobileSearch.length) {
            $(window).on('scroll resize', function() {
                if ($(window).scrollTop() > 120) mobileSearch.slideUp(150);
                else mobileSearch.slideDown(150);
            });
        }
    });
</script>
