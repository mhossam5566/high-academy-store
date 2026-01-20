<?php $__env->startSection('title', 'سلة التسوق'); ?>
<?php $__env->startSection('content'); ?>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <div class="container py-2 mt-5">
        <div class="row py-5">
            <div class="col-12 bg-light p-3">

                
                <div class="d-none d-md-block">
                    <div class="card text-dark bg-light" style="overflow-y: scroll;">
                        <div class="card-header"><strong>سلة التسوق</strong></div>
                        <div class="card-body">
                            <table class="table bg-light table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="fas fa-trash-alt"></i></th>
                                        <th>صورة المنتج</th>
                                        <th>تفاصيل المنتج</th>
                                        <th>السعر</th>
                                        <th>اللون</th>
                                        <th>الحجم</th>
                                        <th>الكمية</th>
                                        <th>الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = Cart::instance('shopping')->content(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr data-row="<?php echo e($item->rowId); ?>">
                                            <td>
                                                <i class="fas fa-times cart-delete desktop-delete text-danger"
                                                    style="cursor:pointer;" data-id="<?php echo e($item->rowId); ?>"></i>
                                            </td>
                                            <td>
                                                <img src="<?php echo e($item->model->image_path); ?>" style="width:100px"
                                                    class="img-thumbnail" alt="">
                                            </td>
                                            <td>
                                                <a href="<?php echo e(route('user.product.show', $item->id)); ?>"
                                                    class="nav-link text-dark"><?php echo e($item->name); ?></a>
                                            </td>
                                            <td><?php echo e(number_format((float)$item->price, 2)); ?> جنيه</td>
                                            <td><?php echo e($item->options->color); ?></td>
                                            <td><?php echo e($item->options->size); ?></td>
                                            <td>
                                                <input type="number" class="form-control qty-input"
                                                    data-id="<?php echo e($item->rowId); ?>" min="1"
                                                    max="<?php echo e($item->options->maxQuantity ?? 99); ?>"
                                                    value="<?php echo e($item->qty); ?>" style="width:60px">
                                            </td>
                                            <td>
                                                <span class="item-subtotal" data-id="<?php echo e($item->rowId); ?>">
                                                    <?php echo e(number_format((float)$item->subtotal(), 2)); ?> جنيه
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                
                <div class="d-block d-md-none">
                    <?php $__currentLoopData = Cart::instance('shopping')->content(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-3 mobile-card" data-row="<?php echo e($item->rowId); ?>">
                            <img src="<?php echo e($item->model->image_path); ?>" class="card-img-top" style="object-fit: cover;" height="200px" alt="Product Image">

                            <div class="card-body">
                                <h5 class="card-title mb-1"><?php echo e($item->name); ?></h5>

                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="card-text my-0">
                                        <strong>السعر:</strong> <?php echo e(number_format((float)$item->price, 2)); ?> جنيه
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <input type="number" class="form-control qty-input" data-id="<?php echo e($item->rowId); ?>"
                                            min="1" max="<?php echo e($item->options->maxQuantity ?? 99); ?>"
                                            value="<?php echo e($item->qty); ?>" style="width:80px">
                                        <label class="form-label"><strong>:الكمية</strong></label>
                                    </div>
                                </div>


                                <p class="card-text my-0 mb-1">
                                    <?php if($item->options->color): ?>
                                        <span><strong>اللون:</strong> <?php echo e($item->options->color); ?></span><br>
                                    <?php endif; ?>
                                    <?php if($item->options->size): ?>
                                        <span><strong>الحجم:</strong> <?php echo e($item->options->size); ?></span><br>
                                    <?php endif; ?>
                                </p>



                                <p class="card-text my-0 mb-3">
                                    <strong>الاجمالي:</strong>
                                    <span class="item-subtotal" data-id="<?php echo e($item->rowId); ?>">
                                        <?php echo e(number_format((float)$item->subtotal(), 2)); ?> جنيه
                                    </span>
                                </p>

                                <button class="btn btn-danger w-100 cart-delete mobile-delete"
                                    data-id="<?php echo e($item->rowId); ?>">
                                    <i class="fas fa-trash-alt"></i> حذف
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

            </div>
        </div>
    </div>

    
    <div class="row my-2 justify-content-center">

        
        <div class="col-md-6 d-none d-md-block bg-light p-5">
            <div class="row mx-1">
                <hr class="w-100">
                <div class="col-12 d-flex justify-content-between align-items-center mb-3">
                    <h4 id="cart-total"><?php echo e(Cart::subtotal()); ?> جنيه</h4>
                    <h4><strong>:الإجمالي الكلي</strong></h4>
                </div>
                <div class="col-12">
                    <a href="<?php echo e(route('user.card.data')); ?>" id="checkout-btn" class="btn btn-primary w-100 text-light">
                        اتمام عملية الدفع
                    </a>
                </div>
            </div>
        </div>

        
        <div class="col-12 d-block d-md-none bg-light p-4 text-center">
            <h4 class="mb-4"><strong>الإجمالي الكلي:</strong> <span id="cart-total-mobile"><?php echo e(Cart::subtotal()); ?>

                    جنيه</span></h4>
            <a href="<?php echo e(route('user.card.data')); ?>" id="checkout-btn-mobile" class="btn btn-primary w-100 text-light">
                اتمام عملية الدفع
            </a>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            // Set up CSRF for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Quantity change handler
            $(document).on('change', '.qty-input', function() {
                let rowId = $(this).data('id'),
                    qty = parseInt($(this).val(), 10);

                $.post("<?php echo e(route('user.cart.update')); ?>", {
                        rowId: rowId,
                        product_qty: qty
                    }, function(resp) {
                        if (!resp.status) {
                            Swal.fire('خطأ', resp.message || 'لم نتمكن من تحديث الكمية.', 'error');
                            return;
                        }
                        // update that row subtotal
                        $(`.item-subtotal[data-id="${rowId}"]`).text(resp.item_subtotal + ' جنيه');
                        // update cart total
                        $('#cart-total, #cart-total-mobile').text(resp.total + ' جنيه');
                        // update cart count in header if you have one:
                        // $('#cart-count').text(resp.cart_count);
                        Swal.fire({
                            icon: 'success',
                            title: 'تم تحديث الكمية',
                            timer: 1000,
                            showConfirmButton: false
                        });
                    }, 'json')
                    .fail(() => {
                        Swal.fire('خطأ', 'خطاء في الخادم، حاول مرة أخرى', 'error');
                    });
            });

            // Delete handler (desktop & mobile)
            $(document).on('click', '.cart-delete', function() {
                let rowId = $(this).data('id'),
                    $cardRow = $(`[data-row="${rowId}"]`);

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'سيتم حذف هذا المنتج من السلة.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                }).then((res) => {
                    if (!res.isConfirmed) return;
                    // inside your `.cart-delete` click handler:
                    $.post("<?php echo e(route('user.cart.delete')); ?>", {
                                cart_id: rowId
                            },

                            function(resp) {
                                if (!resp.status) {
                                    Swal.fire('خطأ', resp.message || 'لم نتمكن من الحذف.', 'error');
                                    return;
                                }
                                // remove row/card
                                $cardRow.remove();
                                // update totals
                                $('#cart-total, #cart-total-mobile').text(resp.total + ' جنيه');
                                // update header count if any
                                // $('#cart-count').text(resp.cart_count);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    timer: 1000,
                                    showConfirmButton: false
                                });
                            }, 'json')
                        .fail(() => {
                            Swal.fire('خطأ', 'خطاء في الخادم، حاول مرة أخرى', 'error');
                        });
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/user/layouts/_cart-list.blade.php ENDPATH**/ ?>