<?php $__env->startSection('title'); ?>
    تسجيل الدخول
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .register {
            transition: color .15s;
            color: black;

            &:focus {
                color: #fff !important;
            }

            &:hover {
                color: #e99239 !important;
                text-decoration: underline !important;
            }
        }
    </style>
    </style>
    <div class="container my-5 pt-3">
        <div class="d-flex align-items-start row g-3 pt-5">
            <div class="nav flex-column nav-pills me-3 col-md-2" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-Login-tab" data-bs-toggle="pill" data-bs-target="#v-pills-Login"
                    type="button" role="tab" aria-controls="v-pills-Login" aria-selected="true">
                    تسجيل دخول
                </button>
                <div class="mt-3 d-flex flex-column align-items-center justify-content-center">
                    <p class="m-0 text-black fs-5">ليس لديك حساب؟</p>
                    <a href="<?php echo e(route('user.register.user')); ?>" class="nav-link register text-center mt-2">
                        !انشاء حساب الان
                    </a>
                </div>
            </div>
            <div class="tab-content col-md-8" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-Login" role="tabpanel"
                    aria-labelledby="v-pills-Login-tab">
                    <div class="card text-dark bg-light mb-3">
                        <div class="card-header">تسجيل دخول <strong></strong></div>
                        <div class="card-body">
                            <form action="<?php echo e(route('user.login.submit')); ?>" method="post">
                                <?php echo csrf_field(); ?> <?php echo $__env->make('user.partials._errors', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">البريد الالكتروني</label>
                                    <input type="email" name="email" class="form-control" required
                                        id="exampleFormControlInput1" />
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">كلمة المرور</label>
                                    <input type="password" name="password" required class="form-control" id="password" />
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                            checked />
                                        <label class="form-check-label" for="remember">تذكرني</label>
                                    </div>
                                </div>
                                <a href="<?php echo e(route('password.request')); ?>">نسيت كلمة المرور</a>
                                <div class="mb-3">
                                    <input type="submit" value="تسجيل" name="login"
                                        class="form-control bg-primary text-light" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user.layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laravel\High_Academy\resources\views/user/login_register.blade.php ENDPATH**/ ?>