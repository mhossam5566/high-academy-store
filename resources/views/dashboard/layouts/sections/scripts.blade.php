<!-- BEGIN: Vendor JS-->
<script src="{{ asset('dashboard/assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/node-waves/node-waves.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/hammer/hammer.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('dashboard/assets/js/form-ajax.js') }}"></script>
<script src="{{ asset('dashboard/assets/js/crud-helper.js') }}"></script>
<script src="{{ asset('dashboard/assets/vendor/libs/toastr/toastr.js') }}"></script>

@yield('vendor-script')
<!-- END: Page Vendor JS-->
<!-- BEGIN: Theme JS-->
<script src="{{ asset('dashboard/assets/js/main.js') }}"></script>


<!-- END: Theme JS-->
<!-- Pricing Modal JS-->
@stack('pricing-script')
<!-- END: Pricing Modal JS-->
<!-- BEGIN: Page JS-->
@yield('page-script')
<!-- END: Page JS-->

<script>
    window.translations = {
        delete_confirm: "{{ __('dashboard.delete_confirm') }}",
        delete_text: "{{ __('dashboard.delete_text') }}",
        delete_btn: "{{ __('dashboard.delete_btn') }}",
        cancel_btn: "{{ __('dashboard.cancel_btn') }}",
        deleting_title: "{{ __('dashboard.deleting_title') }}",
        deleting_wait: "{{ __('dashboard.deleting_wait') }}",
        deleted_title: "{{ __('dashboard.deleted_title') }}",
        deleted_success: "{{ __('dashboard.deleted_success') }}",
        error_title: "{{ __('dashboard.error_title') }}",
        error_text: "{{ __('dashboard.error_text') }}",
        processing: "{{ __('dashboard.processing') }}",
        success: "{{ __('dashboard.success') }}",
        error: "{{ __('dashboard.error') }}",
        unexpected_error: "{{ __('dashboard.unexpected_error') }}",
        operation_success: "{{ __('dashboard.operation_success') }}"
    };
</script>
