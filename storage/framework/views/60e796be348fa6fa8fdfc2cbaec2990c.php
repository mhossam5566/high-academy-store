<!DOCTYPE html>
<html lang="ar">
    <head>
        <meta charset="UTF-8">
        <title>تم الدفع بنجاح</title>
        <style>
            body {
                background-color: #f3f4f8;
                font-size: 18px;
                margin: 20px;
                width: 80%;
                margin: 0 auto;
                font-family: sans-serif;
            }
            p {
                background-color: #fff;
                padding: 15px 20px;
                border-radius: 15px;
            }
            a {
                background-color: #007bff;
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                text-decoration: none;
                display: block;
                text-align: center;
            }
            h1 {
                font-size: 30px;
                font-weight: bold;
                margin-bottom: 20px;
                text-align: center;
            }
            h6 {
                direction: ltr;
            }
            span {
                font-size: small; 
                display: block;
                margin-top: 5px;
            }
        </style>
    </head>
    <body dir="rtl">
        <h1>تم دفع  طلبك بنجاح</h1>
        <div>
            <p>أهلاً <?php echo e($details['name']); ?>,</p>
            <p>تم شراء كود <?php echo e($details['coupon']); ?> بنجاح و يمكنك ايجاده في صفحه اكوادي</p>

            <a style="color: #fff;" href="<?php echo e(env('APP_URL') . '/ar/myvouchers'); ?>">صفحه اكوادي</a> 
            <h6>High Academy Store</h6>
        </div>
    </body>
</html><?php /**PATH E:\laravel\High_Academy\resources\views/emails/success_coupoun.blade.php ENDPATH**/ ?>