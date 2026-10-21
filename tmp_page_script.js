
// ==========================================
// LIBRARY STATISTICS TAB LOGIC
// ==========================================
jQuery(function($) {
    const statsAutoDebounce = { timer: null };

    function scheduleStatsAutoApply(delay) {
        clearTimeout(statsAutoDebounce.timer);
        statsAutoDebounce.timer = setTimeout(fetchStatistics, typeof delay === 'number' ? delay : 200);
    }

    // Initialize Select2 for the stats product filter
    if ($.fn.select2 && $('.select2-stats-product').length) {
        $('.select2-stats-product').select2({
            placeholder: '-- جميع الكتب والأصناف --',
            allowClear: true,
            width: '100%',
            dir: 'rtl'
        });
    }

    // Auto-apply on select changes
    $('#stats_branch_id, #stats_status').on('change', function() {
        scheduleStatsAutoApply(120);
    });

    $('#stats_product_id').on('change', function() {
        scheduleStatsAutoApply(120);
    });

    $('#stats_from_date, #stats_to_date').on('change', function() {
        $('#statsPeriodInput').val('custom');
        $('#quickPeriodGroup button').removeClass('active');
        $('#quickPeriodGroup button[data-period="custom"]').addClass('active');
        scheduleStatsAutoApply(120);
    });

    // Quick period presets
    $('#quickPeriodGroup button').on('click', function() {
        const period = $(this).data('period');
        $('#quickPeriodGroup button').removeClass('active');
        $(this).addClass('active');
        $('#statsPeriodInput').val(period);

        if (period === 'custom') {
            $('#fromDateCol, #toDateCol').slideDown(200);
            $('#stats_from_date').trigger('focus');
        } else {
            $('#fromDateCol, #toDateCol').slideUp(200);
            fetchStatistics();
        }
    });

    // Filter form submit
    $('#statsFilterForm').on('submit', function(e) {
        e.preventDefault();
        fetchStatistics();
    });

    // Reset filter
    $('#btnResetStatsFilter').on('click', function() {
        $('#statsPeriodInput').val('month');
        $('#quickPeriodGroup button').removeClass('active');
        $('#quickPeriodGroup button[data-period="month"]').addClass('active');
        $('#fromDateCol, #toDateCol').hide();
        $('#stats_from_date').val('');
        $('#stats_to_date').val('');
        $('#stats_branch_id').val('all');
        $('#stats_product_id').val('all').trigger('change.select2');
        $('#stats_status').val('all');
        fetchStatistics();
    });

    // Core fetch + render routine
    function fetchStatistics() {
        const formData = $('#statsFilterForm').serialize();
        const exportBaseUrl = "http://localhost/dashboard/orders/library-booking/statistics-export";

        $('#statsLoadingOverlay').show();
        $('#btnExportExcel').attr('href', exportBaseUrl + '?' + formData);

        $.ajax({
            url: "http://localhost/dashboard/orders/library-booking/statistics-data",
            type: 'GET',
            data: formData,
            dataType: 'json',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).done(function(response) {
            if (response && response.status === 'success' && typeof response.html === 'string') {
                $('#statsContentPlaceholder').html(response.html);
                if (response.stats) {
                    $('#tabStatsBadge').text(response.stats.grand_total_books + ' كتاب');
                }
                $('#btnExportExcel').attr('href', exportBaseUrl + '?' + formData);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'تم استلام استجابة غير صالحة من السيرفر، يرجى إعادة المحاولة.',
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
            }
        }).fail(function(xhr) {
            console.error('Error fetching library statistics:', xhr.status, xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'حدث خطأ أثناء تحميل بيانات الإحصائيات، يرجى المحاولة مرة أخرى.',
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
        }).always(function() {
            $('#statsLoadingOverlay').hide();
        });
    }

    // Keep ?tab= in the URL in sync so refresh stays on the same tab
    const statsTabBtn = document.getElementById('tab-stats-btn');
    const bookingTabBtn = document.getElementById('tab-booking-btn');

    if (bookingTabBtn) {
        bookingTabBtn.addEventListener('shown.bs.tab', function() {
            window.history.replaceState(null, '', window.location.pathname + '?tab=booking');
        });
    }
    if (statsTabBtn) {
        statsTabBtn.addEventListener('shown.bs.tab', function() {
            window.history.replaceState(null, '', window.location.pathname + '?tab=statistics');
        });
    }
});

// ==========================================
// LIBRARY BOOKING FORM LOGIC
// ==========================================
$(document).ready(function() {
    // Initialize Select2
    $('.select2-products').select2({
        placeholder: '-- ابحث عن كتاب أو مدرس أو صف --',
        allowClear: true,
        width: '100%',
        dir: 'rtl'
    });

    let selectedItems = {};

    // Payment Type Radio Handlers
    $('input[name="payment_type"]').on('change', function() {
        $('.payment-option-card').removeClass('active');
        $(this).closest('.payment-option-card').addClass('active');

        const val = $(this).val();
        if (val === 'deposit') {
            $('#depositSection').slideDown(200);
            $('#status').val('reserved');
        } else if (val === 'later') {
            $('#depositSection').slideUp(200);
            $('#status').val('reserved');
        } else {
            $('#depositSection').slideUp(200);
            $('#status').val('success');
        }
        calculateTotals();
    });

    // Stock feedback when selecting a product
    $('#productSelector').on('change', function() {
        const select = $(this);
        const option = select.find('option:selected');
        const stock = parseInt(option.data('stock')) || 0;
        const val = select.val();
        const hint = $('#quickMaxStockHint');
        const badge = $('#stockAvailabilityBadge');
        const quickQty = $('#quickQuantity');
        const btnAdd = $('#btnAddProduct');

        if (!val) {
            hint.text('');
            badge.hide();
            quickQty.attr('max', 99).val(1);
            btnAdd.prop('disabled', false);
            return;
        }

        if (stock <= 0) {
            hint.text('(نفد)').removeClass('text-primary').addClass('text-danger');
            quickQty.attr('max', 0).val(0);
            btnAdd.prop('disabled', true);
            badge.html('<span class="badge bg-label-danger py-2 px-3 fs-6 d-inline-flex align-items-center"><i class="ti ti-alert-triangle me-1"></i>عفواً، هذا الكتاب غير متوفر في المخزن حالياً (الكمية: 0). لا يمكن إضافته للطلب.</span>').slideDown(200);
        } else {
            hint.text(`(أقصى حد: ${stock})`).removeClass('text-danger').addClass('text-primary');
            quickQty.attr('max', stock).val(1);
            btnAdd.prop('disabled', false);
            badge.html(`<span class="badge bg-label-success py-2 px-3 fs-6 d-inline-flex align-items-center"><i class="ti ti-check me-1"></i>الكمية المتاحة في المخزن: <strong class="ms-1">${stock}</strong> نسخة</span>`).slideDown(200);
        }
    });

    // Clamp quickQuantity input to available stock
    $('#quickQuantity').on('input change', function() {
        const select = $('#productSelector');
        const option = select.find('option:selected');
        if (select.val()) {
            const stock = parseInt(option.data('stock')) || 0;
            let val = parseInt($(this).val()) || 0;
            if (stock <= 0) {
                $(this).val(0);
            } else if (val > stock) {
                $(this).val(stock);
                Swal.fire({
                    icon: 'warning',
                    title: 'تجاوز الكمية المتاحة',
                    text: `الكمية المتاحة في المخزن لهذا الكتاب هي (${stock}) فقط. تم ضبط الكمية على الحد الأقصى.`,
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
            } else if (val < 1) {
                $(this).val(1);
            }
        }
    });

    // Add Product button click
    $('#btnAddProduct').on('click', function() {
        const select = $('#productSelector');
        const productId = select.val();
        if (!productId) {
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه',
                text: 'يرجى اختيار كتاب أولاً من القائمة.',
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return;
        }

        const option = select.find('option:selected');
        const name = option.data('short-name') || option.data('name');
        const brand = option.data('brand');
        const stage = option.data('stage');
        const price = parseFloat(option.data('price')) || 0;
        const stock = parseInt(option.data('stock')) || 0;
        const qtyToAdd = parseInt($('#quickQuantity').val()) || 1;

        if (stock <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'غير متوفر بالمخزن',
                text: `الكتاب "${name}" رصيده بالمخزن هو (0) حالياً ولا يمكن إضافته للطلب.`,
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-danger' }
            });
            return;
        }

        const currentQty = selectedItems[productId] ? selectedItems[productId].quantity : 0;
        const totalQty = currentQty + qtyToAdd;

        if (totalQty > stock) {
            const remainingCanAdd = Math.max(0, stock - currentQty);
            Swal.fire({
                icon: 'warning',
                title: 'تجاوز الكمية المتاحة',
                html: `
                    <div class="text-start">
                        <p class="mb-2">الكمية المطلوبة تتجاوز رصيد المخزن للكتاب: <strong>${name}</strong></p>
                        <ul class="mb-1 text-muted small">
                            <li>الرصيد المتاح بالمخزن: <strong class="text-dark">${stock}</strong> نسخة</li>
                            <li>المضاف حالياً للطلب: <strong class="text-dark">${currentQty}</strong> نسخة</li>
                            <li>أقصى كمية متبقية يمكنك إضافتها: <strong class="text-primary">${remainingCanAdd}</strong> نسخة</li>
                        </ul>
                    </div>
                `,
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return;
        }

        if (selectedItems[productId]) {
            selectedItems[productId].quantity = totalQty;
        } else {
            selectedItems[productId] = {
                id: productId,
                name: name,
                brand: brand,
                stage: stage,
                price: price,
                stock: stock,
                quantity: qtyToAdd
            };
        }

        renderItemsTable();
        calculateTotals();

        // Reset selector
        select.val('').trigger('change');
        $('#quickQuantity').val(1).attr('max', 99);
        $('#quickMaxStockHint').text('');
        $('#stockAvailabilityBadge').hide();
    });

    // Render items table
    function renderItemsTable() {
        const tbody = $('#itemsTableBody');
        const keys = Object.keys(selectedItems);

        $('#selectedItemsCount').text(keys.length + ' كتب مختارة');

        if (keys.length === 0) {
            tbody.html(`
                <tr id="emptyRow">
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="ti ti-shopping-cart-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                        لم يتم إضافة أي كتب للطلب بعد. اختر كتاباً من القائمة أعلاه واضغط "إضافة".
                    </td>
                </tr>
            `);
            return;
        }

        let html = '';
        let index = 0;

        keys.forEach(id => {
            const item = selectedItems[id];
            const itemTotal = (item.price * item.quantity).toFixed(2);
            const isMaxReached = (item.quantity >= item.stock);

            html += `
                <tr class="item-row" data-id="${item.id}">
                    <td>
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        <div class="fw-bold text-dark">${item.name}</div>
                        <div class="small text-muted">
                            ${item.brand ? '<span class="badge bg-label-info me-1">مدرس: ' + item.brand + '</span>' : ''}
                            ${item.stage ? '<span class="badge bg-label-secondary me-1">' + item.stage + '</span>' : ''}
                            <span class="badge ${item.stock > 0 ? 'bg-label-success' : 'bg-label-danger'} badge-stock">
                                المتاح في المخزن: ${item.stock}
                            </span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-sm justify-content-center" style="max-width: 120px; margin: 0 auto;">
                            <input type="number" step="0.5" min="0" 
                                   name="items[${index}][price]" 
                                   class="form-control text-center item-price-input" 
                                   data-id="${item.id}" 
                                   value="${item.price}">
                            <span class="input-group-text">ج.م</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="d-flex align-items-center justify-content-center gap-1">
                            <button type="button" class="btn btn-sm btn-outline-secondary qty-btn btn-decrease" data-id="${item.id}">
                                <i class="ti ti-minus"></i>
                            </button>
                            <input type="number" min="1" max="${item.stock}" 
                                   name="items[${index}][quantity]" 
                                   class="form-control form-control-sm text-center item-qty-input fw-bold" 
                                   data-id="${item.id}" 
                                   value="${item.quantity}" 
                                   style="width: 60px;">
                            <button type="button" class="btn btn-sm ${isMaxReached ? 'btn-label-secondary' : 'btn-outline-secondary'} qty-btn btn-increase" data-id="${item.id}" ${isMaxReached ? 'title="تم الوصول لأقصى كمية متاحة بالمخزن"' : ''}>
                                <i class="ti ti-plus"></i>
                            </button>
                        </div>
                        ${isMaxReached ? '<small class="text-danger d-block mt-1 font-monospace" style="font-size: 0.7rem;">(الحد الأقصى)</small>' : ''}
                    </td>
                    <td class="text-center fw-bold text-primary">
                        <span class="row-total">${itemTotal}</span> ج.م
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-icon btn-label-danger btn-remove-item" data-id="${item.id}" title="حذف الكتاب">
                            <i class="ti ti-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            index++;
        });

        tbody.html(html);
    }

    // Item quantity / price change events
    $(document).on('click', '.btn-increase', function() {
        const id = $(this).data('id');
        if (selectedItems[id]) {
            const stock = selectedItems[id].stock;
            if (selectedItems[id].quantity >= stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'الحد الأقصى للمخزون',
                    text: `لا يمكنك زيادة الكمية. الرصيد المتاح في المخزن لهذا الكتاب هو (${stock}) نسخة فقط.`,
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
                return;
            }
            selectedItems[id].quantity += 1;
            renderItemsTable();
            calculateTotals();
        }
    });

    $(document).on('click', '.btn-decrease', function() {
        const id = $(this).data('id');
        if (selectedItems[id] && selectedItems[id].quantity > 1) {
            selectedItems[id].quantity -= 1;
            renderItemsTable();
            calculateTotals();
        }
    });

    $(document).on('change input', '.item-qty-input', function() {
        const id = $(this).data('id');
        let val = parseInt($(this).val()) || 1;
        if (selectedItems[id]) {
            const stock = selectedItems[id].stock;
            if (val > stock) {
                Swal.fire({
                    icon: 'warning',
                    title: 'تجاوز الكمية المتاحة',
                    text: `الكمية المدخلة (${val}) تتجاوز المتاح في المخزن (${stock}). تم تعديل الكمية تلقائياً إلى الحد الأقصى (${stock}).`,
                    confirmButtonText: 'حسناً',
                    customClass: { confirmButton: 'btn btn-primary' }
                });
                val = stock;
                $(this).val(val);
            }
            if (val < 1) {
                val = 1;
                $(this).val(1);
            }
            selectedItems[id].quantity = val;
            renderItemsTable();
            calculateTotals();
        }
    });

    $(document).on('change input', '.item-price-input', function() {
        const id = $(this).data('id');
        const val = parseFloat($(this).val()) || 0;
        if (selectedItems[id]) {
            selectedItems[id].price = Math.max(0, val);
            calculateTotals();
        }
    });

    $(document).on('click', '.btn-remove-item', function() {
        const id = $(this).data('id');
        delete selectedItems[id];
        renderItemsTable();
        calculateTotals();
    });

    // Branch Radio Handlers
    $('input[name="shipping_method_id"]').on('change', function() {
        $('input[name="shipping_method_id"]').closest('.custom-option').removeClass('border-primary bg-label-primary');
        $(this).closest('.custom-option').addClass('border-primary bg-label-primary');
    });

    // Shipping, discount, deposit change
    $('#discount, #deposit_amount').on('change input', function() {
        calculateTotals();
    });

    // Calculate totals & balances
    function calculateTotals() {
        let subtotal = 0;
        Object.values(selectedItems).forEach(item => {
            subtotal += (item.price * item.quantity);
        });

        const discount = parseFloat($('#discount').val()) || 0;
        const grandTotal = Math.max(0, subtotal - discount);

        const paymentType = $('input[name="payment_type"]:checked').val() || 'full';
        let paidAmount = 0;
        let remainingAmount = 0;

        if (paymentType === 'full') {
            paidAmount = grandTotal;
            remainingAmount = 0;
        } else if (paymentType === 'deposit') {
            const enteredDeposit = parseFloat($('#deposit_amount').val()) || 0;
            paidAmount = Math.min(grandTotal, Math.max(0, enteredDeposit));
            remainingAmount = Math.max(0, grandTotal - paidAmount);
        } else {
            // later / no deposit
            paidAmount = 0;
            remainingAmount = grandTotal;
        }

        $('#subtotalDisplay').text(subtotal.toFixed(2) + ' ج.م');
        $('#discountDisplay').text('- ' + discount.toFixed(2) + ' ج.م');
        $('#grandTotalDisplay').text(grandTotal.toFixed(2) + ' ج.م');
        $('#paidDisplay').text(paidAmount.toFixed(2) + ' ج.م');
        $('#remainingDisplay').text(remainingAmount.toFixed(2) + ' ج.م');
    }

    // Form submission validation
    $('#libraryBookingForm').on('submit', function(e) {
        const keys = Object.keys(selectedItems);
        if (keys.length === 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'تنبيه',
                text: 'يجب اختيار وإضافة كتاب واحد على الأقل للطلب قبل الحفظ.',
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return false;
        }

        const studentName = $('#student_name').val().trim();
        const mobile = $('#mobile').val().trim();

        if (!studentName || !mobile) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'بيانات غير مكتملة',
                text: 'يرجى التأكد من كتابة اسم الطالب ورقم الهاتف بشكل صحيح.',
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-primary' }
            });
            return false;
        }

        // Validate stock for all selected items before sending
        let stockError = null;
        Object.values(selectedItems).forEach(item => {
            if (item.stock <= 0) {
                stockError = `الكتاب "${item.name}" رصيده بالمخزن (0) ولا يمكن إتمام الحجز به. يرجى حذفه أولاً.`;
            } else if (item.quantity > item.stock) {
                stockError = `الكمية المطلوبة من كتاب "${item.name}" (${item.quantity} نسخة) تتجاوز الرصيد المتاح بالمخزن (${item.stock} نسخة).`;
            }
        });

        if (stockError) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'خطأ في الكميات والمخزون',
                text: stockError,
                confirmButtonText: 'حسناً',
                customClass: { confirmButton: 'btn btn-danger' }
            });
            return false;
        }

        // Disable submit button to prevent double-submit
        $('#btnSubmitOrder').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>جاري حفظ الطلب...');
    });
});

