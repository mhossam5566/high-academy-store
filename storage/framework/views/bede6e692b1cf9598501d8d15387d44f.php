<?php
use ArPHP\I18N\Arabic;
$Arabic = new Arabic();
function reverseWords($string)
{
    // Split the string into an array of words
    $words = explode(' ', $string);

    // Reverse the array of words
    $reversedWords = array_reverse($words);

    // Join the reversed array back into a string
    return implode(' ', $reversedWords);
}

$grouped = $orders->groupBy(fn($order) => $order->shipping->name ?? $order->shipping_method);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <style type="text/css" media="all">
        >body {
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 14px;
            word-wrap: break-word;
            white-space: normal;
            padding: 0;
            margin: 0;
        }


        .export {
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
        }

        .export,
        .export th,
        .export td {
            border: 1px solid black;
        }

        .export th,
        .export td {
            padding: 8px;
            text-align: right;
            word-break: break-word;
            white-space: normal;
        }

        .success {
            margin: 0;
            width: 100%;
            border-collapse: collapse;
            direction: rtl;
            padding: 10px;
        }

        .cell {
            padding: 10px;
            vertical-align: top;
            /* Aligns content to the top */
        }

        .cell1 {
            width: 70%;
        }

        .cell3 {
            width: 30%;
            border-right: 1px solid #16404D;
            height: fit-content;
        }

        .table-bordered,
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #16404D;
        }

        .success th,
        .success td {
            padding: 8px;
            text-align: right;
            word-break: break-word;
            white-space: normal;
        }

        .page {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
            page-break-after: always;
        }
    </style>
</head>

<body dir="rtl">
    
    <?php
        $ordersForShipping = collect($grouped)
            ->only(['شحن لاقرب مكتب بريد', 'شحن لباب البيت'])
            ->collapse();
    ?>

    <?php $__currentLoopData = $ordersForShipping->chunk(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunkIndex => $twoOrders): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <!-- Group orders in chunks of 2 -->
        <div class="page" style="padding: 0; margin: 0;">
            <?php $__currentLoopData = $twoOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    // dd($order);
                    $parts = explode(' - ', $order->address);
                    $governorate = $parts[0] ?? '';
                    $city = $parts[1] ?? '';
                ?>
                <div style="border: 1px dashed #1c2b30; padding: 5px; margin: 0px 0 20px 0;">
                    <p style="text-align: center; font-size: 26px; font-weight: 900; margin-bottom: 0px; margin-top: 0px;">
                        High Academy Store</p>

                    <table class="success">
                        <tr>
                            <td class="cell cell1" style="font-size: 18px;">
                                <h2 style="color: #1c2b30; font-size:20px; margin: 0px;">
                                    المرسل إليه
                                </h2>

                                <br>
                                <p style="margin: 0px;"><b style="color: #1c2b30;">الاسم</b>:
                                    <?php echo e($order->name ?? $order->user->name); ?>

                                </p>
                                <br />
                                <p><b>المحافظه</b>: <?php echo e($governorate); ?></p>
                                <p><b>المدينة / المركز</b>: <?php echo e($city); ?></p>
                                <br />
                                <p style="margin: 0px;"><b style="color: #1c2b30;">اسم مكتب
                                        البريد</b>: <?php echo e($order->near_post ? $order->near_post : ''); ?></p>
                                <br />
                                <p style="margin: 0px;"><b style="color: #1c2b30;">العنوان
                                        بالتفصيل</b>: <?php echo e($order->address); ?>

                                    <br /> <?php echo e($order->address2 ?? $order->users->address); ?>

                                </p>
                                <br />
                                <p style="margin: 0px; text-align: center;"><b style="color: #1c2b30;">رقم الموبايل</b>:
                                    0<?php echo e($order->mobile); ?></p>
                                <br />
                                <p style="margin: 0px;"><b style="color: #1c2b30;">رقم
                                        احتياطي</b>: <?php echo e($order->temp_mobile ?? $order->user->phone); ?></p>
                            </td>
                            <td class="cell cell3">
                                <h2 style="color: #118B50; font-size:20px; margin: 0px;">الراسل</h2>
                                <br>
                                <p style="font-size: 14px; color: #118B50; margin: 0px;"><b style="color: #118B50;">الاسم</b>:
                                    مكتبة يُسْر عنهم</p>
                                <br />
                                <p style="font-size: 14px; color: #118B50; margin: 0px;"><b style="color: #118B50;">المهندس</b>:
                                    احمد علام</p>
                                <br />
                                <p style="font-size: 14px; color: #118B50; margin: 0px;"><b style="color: #118B50;">رقم
                                        الموبيل</b>: 01060683708</p>
                                <br />
                                <p style="font-size: 14px; color: #118B50; margin: 0px;"><b style="color: #118B50;">العنوان</b>:
                                    المنوفية - شبين الكوم <br> امام نادى
                                    التجارة
                                </p>
                                <br />
                                <?php if($order->shipping_method == 2): ?>
                                    <p><b>نوع الشحن</b>:
                                        <span style="color: red;">البريد السريع</span>
                                    </p>
                                <?php else: ?>
                                    <p><b>نوع الشحن</b>:
                                        <span><?php echo e(optional($order->shipping)->name); ?></span>
                                    </p>
                                <?php endif; ?>

                                <br />
                                <p style="font-size: 14px; color: #118B50; margin: 0px;"><b style="color: #118B50;">رقم
                                        الطلب</b>: <?php echo e($order->id); ?></p>
                                <br />
                            </td>
                        </tr>
                    </table>
                    <table class="success table-bordered" style="margin-top: 5px;">
                        <thead>
                            <tr>
                                <th style="color: #16404D; text-style: bold;"><?php echo e('الكتب المطلوبة'); ?></th>
                                <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td style="color: #16404D;">
                                        <?php echo e($detail->products->short_name ?? $detail->products->name); ?>

                                        <?php echo e(@$detail->color ?? ' '); ?> <?php echo e(' ' . @$detail->size ?? ' '); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th style="color: #16404D; text-style: bold;"><?php echo e('العدد المطلوب'); ?></th>
                                <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <td style="color: #16404D;"><?php echo e($detail->amout); ?></td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <?php $__currentLoopData = collect($grouped)->except(['شحن لاقرب مكتب بريد', 'شحن لباب البيت']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipping => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <h2><?php echo e($shipping); ?></h2>
        <table class="export">
            <thead>
                <tr>
                    <th>رقم الطلب</th>
                    <th>الاسم</th>
                    <th>الهاتف</th>
                    <th>نوع الشحن</th>
                    <th>العنوان</th>
                    <th>عدد الكتب</th>
                    <th>الكتب المطلوبة</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $o; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($order->id); ?></td>
                        <td><?php echo e($order->name); ?></td>
                        <td><?php echo e($order->mobile); ?></td>
                        <td><?php echo e(($order->shipping_method == 2) ? 'البريد السريع' : (optional($order->shipping)->name ?: $order->shipping_method)); ?>

                        </td>
                        <td><?php echo e($order->address); ?></td>

                        <td>
                            <?php echo e($order->orderDetails->sum('amout')); ?>

                        </td>
                        <td>
                            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                x<?php echo e($d->amout); ?> <?php echo e($d->products->short_name ?? 'محذوف'); ?><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
        </table>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <p>
        <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipping => $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <b><?php echo e($shipping); ?></b> (<?php echo e($o->count()); ?>)
            <br>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </p>
    <h2>الكتب المطلوبة</h2>
    <?php
        $allDetails = collect($orders)->flatMap(function ($order) {
            return $order->orderDetails;
        });
        $mergedBooks = $allDetails->groupBy(fn($detail) => $detail->products->id)
            ->map(function ($group) {
                return [
                    'name' => $group->first()->products->short_name ?? $group->first()->products->name,
                    'total' => $group->sum('amout'),
                ];
            });
    ?>
    <table class="export">
        <thead>
            <tr>
                <th>اسم الكتاب</th>
                <th>الكمية</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $mergedBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($book['name']); ?></td>
                    <td><?php echo e($book['total']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>

</html>
<?php /**PATH E:\laravel\High_Academy\resources\views/dashboard/pages/order/groupedExport.blade.php ENDPATH**/ ?>