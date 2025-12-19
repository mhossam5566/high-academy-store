document.addEventListener('DOMContentLoaded', () => {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-left",
        "timeOut": "5000"
    };

    const showToast = (message, type = 'success') => {
        if (type === 'success') toastr.success(message);
        else if (['danger', 'error'].includes(type)) toastr.error(message);
        else if (type === 'info') toastr.info(message);
        else if (type === 'warning') toastr.warning(message);
        else toastr.info(message);
    };

    document.addEventListener('submit', async (e) => {
        const form = e.target.closest('form[data-ajax]');
        if (!form) return;

        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            form.classList.add('was-validated');
            return;
        }
        e.preventDefault();

        const action = form.getAttribute('action');
        const method = form.getAttribute('method') || 'POST';
        const formData = new FormData(form);
        const submitBtn = form.querySelector('[type="submit"]');
        const originalHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <div class="d-flex align-items-center justify-content-center gap-2">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span>جاري المعالجة...</span>
            </div>
        `;

        try {
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            const response = await fetch(action, {
                method: method.toUpperCase(),
                headers,
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                showToast(data.message || 'تم بنجاح', 'success');

                setTimeout(() => {
                    if (typeof window.afterAjaxSuccess === 'function') {
                        window.afterAjaxSuccess(data, form);
                    } else {
                        const redirect = form.getAttribute('data-redirect') || data.redirect;
                        if (redirect) window.location.href = redirect;
                        else {
                            const modal = form.closest('.modal');
                            if (modal) $(modal).modal('hide');
                        }
                    }
                }, 800);
            } else {
                if (response.status === 401) {
                    showToast('يجب تسجيل الدخول أولاً', 'danger');
                    setTimeout(() => window.location.href = '/auth/login', 1500);
                } else {
                    throw data;
                }
            }
        } catch (error) {
            if (error.errors) {
                Object.values(error.errors).flat().forEach(msg => showToast(msg, 'danger'));
            } else {
                showToast(error.message || 'حدث خطأ غير متوقع', 'danger');
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalHtml;
        }
    });
});
