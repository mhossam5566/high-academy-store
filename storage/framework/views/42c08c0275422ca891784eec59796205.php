<style>
    .category_text {
        font-size: 0.7rem;
    }

    .categ_img {
        width: 100px;
        height: 100px;
    }

    .categ_img_code {
        width: 100px;
        height: 100px;
    }

    @media (min-width: 576px) {
        .category_text {
            font-size: 1rem;
        }

        .categ_img_code {
            transform: scale(1.2);
        }

    }
</style>
<?php if(isset($main_categories)): ?>
<?php $__currentLoopData = $main_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="h-100 col-6">
    <div class="w-auto py-3 rounded-2 bg-white category">
        <?php if($category->name == 'اكواد مدرسين الاونلاين'): ?>
            <a href="<?php echo e(route('user.evouchers')); ?>"
                class="d-flex flex-column justify-content-center align-items-center gap-3 text-decoration-none text-black text-center">

                <?php if($category->icon_image): ?>
                    <img src="<?php echo e(asset('storage/' . $category->icon_image)); ?>" alt="<?php echo e($category->name); ?>"
                        class="img-fluid categ_img_code">
                <?php endif; ?>
                <strong class="category_text"><?php echo e($category->name); ?></strong>
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('user.shop', ['main_category_id' => $category->id])); ?>"
                class="d-flex flex-column justify-content-center align-items-center gap-3 text-decoration-none text-black text-center">

                <?php if($category->icon_image): ?>
                    <img src="<?php echo e(asset('storage/' . $category->icon_image)); ?>" alt="<?php echo e($category->name); ?>"
                        class="img-fluid categ_img">
                <?php endif; ?>
                <strong class="category_text"><?php echo e($category->name); ?></strong>
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php else: ?>
    <p>Error: $main_categories is missing!</p>
<?php endif; ?>
<?php /**PATH E:\laravel\High_Academy\resources\views/user/layouts/categories.blade.php ENDPATH**/ ?>