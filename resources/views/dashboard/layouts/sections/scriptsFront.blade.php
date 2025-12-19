<!-- BEGIN: Vendor JS-->
<script src="{{ asset('dashboard/assets/vendor/js/dropdown-hover.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/js/mega-dropdown.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset('dashboard/assets/js/front-main.js') }}"></script>
<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')

<script src="{{ asset('dashboard/assets/js/ui-toasts.js') }}"></script>
<!-- END: Page JS-->
