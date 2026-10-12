<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderLog;
use App\Mail\SuccessPaid;
use Illuminate\Http\Request;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Nafezly\Payments\Classes\TapPayment;
use Gloudemans\Shoppingcart\Facades\Cart;
use Nafezly\Payments\Classes\FawryPayment;
use Nafezly\Payments\Classes\HyperPayPayment;

class PaymentController extends Controller
{
    /**
     * Log product state changes with detailed context
     */
    private function logStateChange($product, $oldState, $newState, $context, $additionalInfo = [])
    {
        $stateNames = [
            0 => 'Unavailable',
            1 => 'Available',
            2 => 'Reserved'
        ];

        $logData = [
            'product_id' => $product->id,
            'product_name' => $product->name ?? 'Unknown',
            'old_state' => $oldState,
            'new_state' => $newState,
            'old_state_name' => $stateNames[$oldState] ?? 'Unknown',
            'new_state_name' => $stateNames[$newState] ?? 'Unknown',
            'quantity' => $product->quantity,
            'context' => $context,
            'timestamp' => now()->toDateTimeString()
        ];

        // Add any additional info
        $logData = array_merge($logData, $additionalInfo);

        Log::info("PRODUCT STATE CHANGE: {$context}", $logData);
    }

    /**
     * كتابة لوج إلى قاعدة البيانات
     *
     * @param mixed      &$dbLogState  حالة مشتركة [order_id, process_id, ip]
     * @param string      $message
     * @param string      $level       info|success|warning|error
     * @param string|null $step
     * @param array       $context
     */
    private function logToDB(array &$dbLogState, string $message, string $level = 'info', ?string $step = null, array $context = []): void
    {
        try {
            OrderLog::create([
                'order_id'   => $dbLogState['order_id'] ?? null,
                'process_id' => $dbLogState['process_id'] ?? null,
                'level'      => $level,
                'step'       => $step,
                'message'    => $message,
                'context'    => $context ?: null,
                'ip'         => $dbLogState['ip'] ?? null,
            ]);
        } catch (\Throwable $e) {
            // fallback to laravel log so we never lose the message
            Log::warning('OrderLog DB write failed: ' . $e->getMessage());
            Log::info("[{$level}] {$message}", $context);
        }
    }

    public function payment($order)
    {
        $orderData = Order::find($order);
        /*
        $payment = new TapPayment();
        $response = $payment->pay(
            $orderData->total,
            $user_id = 1,
            $user_first_name = $orderData->users->name,
            $user_last_name = $orderData->users->name,
            $user_email = $orderData->users->email,
            $user_phone = $orderData->users->phone,
            $source = "src_cards"
        );
        */
        $payment = new FawryPayment();
        $response = $payment->setUserFirstName($orderData->users->name)
            ->setUserLastName($orderData->users->name)
            ->setUserEmail($orderData->users->email)
            ->setUserPhone($orderData->users->phone)
            ->setAmount($orderData->total)
            ->pay();

        $orderData->update([
            "payment_id" => $response['payment_id']
        ]);
        return redirect($response['redirect_url']);
    }

    public function callBack(Request $request)
    {
        $status = $request->orderStatus;
        $method = $request->paymentMethod;

        if (isset($method) && $method == "PayAtFawry") {
            return redirect()->route('genral.payment.pend');
        }

        if (isset($method) && $method == "MWALLET") {
            return redirect()->route('genral.payment.pend');
        }

        if (isset($status) && $status == "PAID") {

            return redirect()->route('genral.payment.success');
        } else {

            return redirect()->route('genral.payment.failed');
        }

        //return redirect()->route("user.order.details", $order->id);
    }

    public function paymentSuccess(Request $request)
    {
        $state = "success";
        $compact = compact('state');
        return view('payment_result', $compact);
    }

    public function paymentaFiled(Request $request)
    {
        $state = "fail";
        $compact = compact('state');
        return view('payment_result', $compact);
    }

    public function paymentPend(Request $request)
    {
        $state = "pend";
        $ref = $request->referenceNumber;
        $compact = compact('state', 'ref');
        return view('payment_result', $compact);
    }

    public function fawry_webhook(Request $request)
    {
        $data = $request->all();

        // إنشاء معرف فريد للعملية
        $processId = 'fawry_' . time() . '_' . uniqid();

        // حالة اللوج المشتركة (قبل معرفة الطلب)
        $dbLog = [
            'order_id'   => null,
            'process_id' => $processId,
            'ip'         => $request->ip(),
        ];

        // تحويل المصفوفة إلى JSON
        $jsonText = json_encode($data, JSON_PRETTY_PRINT);

        // بداية التسجيل
        $this->logToDB($dbLog, '=== FAWRY WEBHOOK PROCESS START ===', 'info', 'START', [
            'process_id'     => $processId,
            'timestamp'      => date('Y-m-d H:i:s'),
            'ip'             => $request->ip(),
            'method'         => $request->method(),
            'received_data'  => $data,
        ]);

        // التحقق من وجود orderStatus
        $this->logToDB($dbLog, 'STEP 1: Checking if orderStatus exists', 'info', 'STEP 1');

        if (!isset($request->orderStatus)) {
            $this->logToDB($dbLog, 'ERROR: orderStatus not found in request', 'error', 'STEP 1');
            $this->logToDB($dbLog, '=== PROCESS TERMINATED - NO ORDER STATUS ===', 'error', 'END');
            return;
        }

        $this->logToDB($dbLog, 'SUCCESS: orderStatus found - ' . $request->orderStatus, 'success', 'STEP 1');

        // استخراج البيانات المطلوبة
        $this->logToDB($dbLog, 'STEP 2: Extracting required data', 'info', 'STEP 2');

        $fawryRefNumber       = $data['fawryRefNumber'];
        $merchantRefNumber    = $data['merchantRefNumber'];
        $paymentAmount        = number_format($data['paymentAmount'], 2, '.', '');
        $orderAmount          = number_format($data['orderAmount'], 2, '.', '');
        $orderStatus          = $data['orderStatus'];
        $paymentMethod        = $data['paymentMethod'];
        $paymentReferenceNumber = isset($data['paymentRefrenceNumber']) ? $data['paymentRefrenceNumber'] : '';
        $secureKey            = config('nafezly-payments.FAWRY_SECRET');

        $this->logToDB($dbLog, 'Extracted payment data', 'info', 'STEP 2', [
            'fawryRefNumber'         => $fawryRefNumber,
            'merchantRefNumber'      => $merchantRefNumber,
            'paymentAmount'          => $paymentAmount,
            'orderAmount'            => $orderAmount,
            'orderStatus'            => $orderStatus,
            'paymentMethod'          => $paymentMethod,
            'paymentReferenceNumber' => $paymentReferenceNumber,
        ]);

        // التحقق من صحة التوقيع
        $this->logToDB($dbLog, 'STEP 3: Verifying signature', 'info', 'STEP 3');

        $concatenatedString = $fawryRefNumber . $merchantRefNumber . $paymentAmount . $orderAmount . $orderStatus . $paymentMethod . $paymentReferenceNumber . $secureKey;
        $sha256Digest       = hash('sha256', $concatenatedString);

        $this->logToDB($dbLog, 'Signature check', 'info', 'STEP 3', [
            'generated_sha256'  => $sha256Digest,
            'received_signature' => $request->messageSignature,
        ]);

        if ($request->messageSignature !== $sha256Digest) {
            $this->logToDB($dbLog, 'ERROR: Signature verification failed!', 'error', 'STEP 3');
            $this->logToDB($dbLog, '=== PROCESS TERMINATED - INVALID SIGNATURE ===', 'error', 'END');
            return;
        }

        $this->logToDB($dbLog, 'SUCCESS: Signature verified', 'success', 'STEP 3');

        // البحث عن الطلب
        $this->logToDB($dbLog, 'STEP 4: Finding order', 'info', 'STEP 4', [
            'searching_order_id' => $merchantRefNumber,
            'searching_user_id'  => $request->customerMerchantId,
        ]);

        $order = Order::where('id', $request->merchantRefNumber)->where('user_id', $request->customerMerchantId)->first();

        if (!$order) {
            $this->logToDB($dbLog, 'ERROR: Order not found!', 'error', 'STEP 4');
            $this->logToDB($dbLog, '=== PROCESS TERMINATED - ORDER NOT FOUND ===', 'error', 'END');
            return;
        }

        // ربط اللوج بالطلب من الآن
        $dbLog['order_id'] = $order->id;

        $this->logToDB($dbLog, "SUCCESS: Order found - ID: {$order->id}", 'success', 'STEP 4', [
            'order_id'      => $order->id,
            'order_status'  => $order->status,
            'order_is_paid' => $order->is_paid,
            'order_tracker' => $order->tracker,
        ]);

        // معالجة حالة الطلب
        $this->logToDB($dbLog, "STEP 5: Processing order status - {$orderStatus}", 'info', 'STEP 5');

        if ($request->orderStatus == 'PAID') {
            $this->logToDB($dbLog, 'Processing PAID status', 'info', 'STEP 5');

            $order->is_paid = 1;
            $this->logToDB($dbLog, 'Set is_paid to 1', 'info', 'STEP 5');

            $hasReservedProduct = false;
            $this->logToDB($dbLog, 'Checking order details for reserved products', 'info', 'STEP 5');

            foreach ($order->orderDetails as $detail) {
                if (!empty($detail->products)) {
                    $productInfo = [
                        'detail_id'  => $detail->id,
                        'product_id' => $detail->product_id,
                        'state'      => $detail->products->state,
                        'quantity'   => $detail->products->quantity,
                    ];
                    $this->logToDB($dbLog, "Product found - ID: {$detail->product_id}, State: {$detail->products->state}, Qty: {$detail->products->quantity}", 'info', 'STEP 5', $productInfo);
                    Log::info("Webhook (Before Update) - Order ID: {$order->id}, Product ID: {$detail->product_id}, State: {$detail->products->state}, Quantity: {$detail->products->quantity}");

                    if ($detail->products->state == 2) {
                        $hasReservedProduct = true;
                        $this->logToDB($dbLog, "Reserved product detected - ID: {$detail->product_id}", 'warning', 'STEP 5');
                    }
                } else {
                    $this->logToDB($dbLog, "No product found for detail ID: {$detail->id}", 'warning', 'STEP 5');
                }
            }

            $orderStatus    = $hasReservedProduct ? 'reserved' : 'success';
            $order->status  = $orderStatus;
            $order->tracker = 'shipped'; // second stage

            $this->logToDB($dbLog, "Set order status to: {$orderStatus}", 'info', 'STEP 5');
            $this->logToDB($dbLog, 'Set tracker to: shipped', 'info', 'STEP 5');

            // إرسال الإيميل
            try {
                $this->logToDB($dbLog, 'Sending success email to: ' . $order->user->email, 'info', 'STEP 5');
                Mail::to($order->user->email)->send(new SuccessPaid($order));
                $this->logToDB($dbLog, 'Email sent successfully', 'success', 'STEP 5');
            } catch (\Exception $e) {
                $this->logToDB($dbLog, 'ERROR sending email: ' . $e->getMessage(), 'error', 'STEP 5');
            }

            $order->save();
            $this->logToDB($dbLog, 'Order saved successfully', 'success', 'STEP 5');

            // Send WhatsApp notification for successful payment
            $this->sendOrderWhatsapp($order, $orderStatus, $dbLog);

        } elseif ($request->orderStatus == 'UNPAID') {
            $this->logToDB($dbLog, 'Processing UNPAID status', 'info', 'STEP 5');

            $order->is_paid = 2;
            $order->status  = 'cancelled';
            $order->response = $jsonText;

            $this->logToDB($dbLog, 'Set is_paid to 2, status to cancelled', 'info', 'STEP 5');

            // Restore product quantities and handle state properly
            $this->logToDB($dbLog, 'Starting product quantity restoration', 'info', 'STEP 5');

            foreach ($order->orderDetails as $detail) {
                $this->logToDB($dbLog, "Processing detail ID: {$detail->id}, product_id: {$detail->product_id}, amount: {$detail->amout}", 'info', 'STEP 5');

                $product = Product::find($detail->product_id);
                if (!$product) {
                    $this->logToDB($dbLog, "Product not found for ID: {$detail->product_id}", 'warning', 'STEP 5');
                    continue;
                }

                $originalState    = $product->state;
                $originalQuantity = $product->quantity;

                $this->logToDB($dbLog, "Product before update - State: {$originalState}, Quantity: {$originalQuantity}", 'info', 'STEP 5', [
                    'product_id'       => $product->id,
                    'original_state'   => $originalState,
                    'original_qty'     => $originalQuantity,
                ]);

                // Restore quantity
                $product->increment('quantity', $detail->amout);
                $product->refresh();

                $this->logToDB($dbLog, "Quantity restored by {$detail->amout}, new quantity: {$product->quantity}", 'info', 'STEP 5');

                // Smart state update logic for cancelled orders
                if ($originalState == 2) {
                    $product->state = 2;
                    $this->logToDB($dbLog, 'Kept state as reserved (2)', 'info', 'STEP 5');
                } elseif ($originalState == 0 && $product->quantity > 0) {
                    $product->state = 1;
                    $this->logToDB($dbLog, 'Changed state from unavailable (0) to available (1)', 'info', 'STEP 5');
                } elseif ($originalState == 1) {
                    $product->state = $product->quantity > 0 ? 1 : 0;
                    $this->logToDB($dbLog, "Updated state based on quantity - new state: {$product->state}", 'info', 'STEP 5');
                }

                $this->logStateChange($product, $originalState, $product->state, 'Order cancelled', [
                    'order_id'    => $order->id,
                    'order_status' => $order->status,
                ]);

                $product->save();
                $this->logToDB($dbLog, "Product saved - Final state: {$product->state}, Final quantity: {$product->quantity}", 'success', 'STEP 5', [
                    'product_id'    => $product->id,
                    'final_state'   => $product->state,
                    'final_qty'     => $product->quantity,
                ]);
            }

            $order->save();
            $this->logToDB($dbLog, 'Order saved successfully', 'success', 'STEP 5');

            // Send WhatsApp notification for cancelled payment
            $this->sendOrderWhatsapp($order, 'cancelled', $dbLog);
        } else {
            $this->logToDB($dbLog, "Unknown order status: {$request->orderStatus}", 'warning', 'STEP 5');
        }

        $this->logToDB($dbLog, '=== FAWRY WEBHOOK PROCESS COMPLETED SUCCESSFULLY ===', 'success', 'END', [
            'final_status'  => $order->status,
            'final_is_paid' => $order->is_paid,
            'ended_at'      => date('Y-m-d H:i:s'),
        ]);
    }


    public function cronjob()
    {
        $fawry_orders = Order::where("status", "new")->where("method", "Fawry Pay")->where('created_at', '<', Carbon::now()->subHours(4))->get();
        $fawryWallet_orders = Order::where("status", "new")->where("method", "Fawry WALLET")->where('created_at', '<', Carbon::now()->subHours(4))->get();

        // إلغاء طلبات الكوبونات المنتظرة بعد 4 ساعات
        $voucher_orders = \App\Models\VouchersOrder::where("state", "pending")->where('created_at', '<', Carbon::now()->subHours(4))->get();

        $orders = Order::where("tracker", "delivered")->where('updated_at', '<', Carbon::now()->subHours(48))->get();
        $cart = Cart::instance('shopping')->content();
        $expired_cart_items = $cart->filter(function ($item) {
            // 'added_at' is stored in options when adding to cart
            $addedAt = optional($item->options)->added_at;
            if ($addedAt == null) {
                return false;
            }
            // return $addedAt < Carbon::now()->subHours(3)->timestamp;
            return $addedAt < Carbon::now()->subMinutes(5)->timestamp;
        });

        foreach ($expired_cart_items as $item) {
            $product = Product::find(optional($item->model)->id);
            if (!$product) {
                continue;
            }

            // Store original state before quantity restoration
            $originalState = $product->state;

            // Atomic restore
            $product->increment('quantity', $item->qty);
            $product->refresh();

            // Smart state update logic
            if ($originalState == 2) {
                // If it was reserved, keep it reserved regardless of quantity
                $product->state = 2;
            } elseif ($originalState == 0 && $product->quantity > 0) {
                // If it was unavailable due to no stock, and now has stock, make it available
                $product->state = 1;
            }
            // If state was 1 and quantity > 0, keep it as 1
            // If quantity is still 0, keep state as 0

            $this->logStateChange($product, $originalState, $product->state, "Cart item expired", [
                'cart_item_id' => $item->rowId,
                'cart_item_name' => $item->name
            ]);

            $product->save();
            Cart::instance('shopping')->remove($item->rowId);
        }

        foreach ($fawry_orders as $o) {
            $order = Order::find($o->id);
            if (!$order) {
                continue;
            }
            $order->status = "cancelled";
            $order->save();
            foreach ($order->orderDetails as $detail) {
                $product = Product::find($detail->product_id);
                if (!$product) {
                    continue;
                }

                // Store original state before quantity restoration
                $originalState = $product->state;

                $product->increment('quantity', $detail->amout);
                $product->refresh();

                // Smart state update logic for cancelled orders
                if ($originalState == 2) {
                    // If it was reserved, keep it reserved regardless of quantity
                    $product->state = 2;
                } elseif ($originalState == 0 && $product->quantity > 0) {
                    // If it was unavailable due to no stock, and now has stock, make it available
                    $product->state = 1;
                } elseif ($originalState == 1) {
                    // If it was available, keep it available if quantity > 0
                    $product->state = $product->quantity > 0 ? 1 : 0;
                }

                $this->logStateChange($product, $originalState, $product->state, "Order cancelled", [
                    'order_id' => $order->id,
                    'order_status' => $order->status
                ]);

                $product->save();
            }
        }

        foreach ($orders as $o) {
            $order = Order::find($o->id);
            $order->tracker = "success";
            $order->save();
        }

        foreach ($fawryWallet_orders as $o) {
            $order = Order::find($o->id);
            if (!$order) {
                continue;
            }
            $order->status = "cancelled";
            $order->save();
            foreach ($order->orderDetails as $detail) {
                $product = Product::find($detail->product_id);
                if (!$product) {
                    continue;
                }

                // Store original state before quantity restoration
                $originalState = $product->state;

                $product->increment('quantity', $detail->amout);
                $product->refresh();

                // Smart state update logic for cancelled orders
                if ($originalState == 2) {
                    // If it was reserved, keep it reserved regardless of quantity
                    $product->state = 2;
                } elseif ($originalState == 0 && $product->quantity > 0) {
                    // If it was unavailable due to no stock, and now has stock, make it available
                    $product->state = 1;
                } elseif ($originalState == 1) {
                    // If it was available, keep it available if quantity > 0
                    $product->state = $product->quantity > 0 ? 1 : 0;
                }

                $this->logStateChange($product, $originalState, $product->state, "Order cancelled", [
                    'order_id' => $order->id,
                    'order_status' => $order->status
                ]);

                $product->save();
            }
        }

        // إلغاء طلبات الكوبونات المنتظرة بعد 4 ساعات
        foreach ($voucher_orders as $voucherOrder) {
            $voucherOrder->state = "cancelled";
            $voucherOrder->save();
        }
    }

    /**
     * Send WhatsApp notification to customer on order status change
     *
     * @param array|null $dbLog  pass the shared dbLog state for DB logging, or null to skip
     */
    private function sendOrderWhatsapp(Order $order, string $status, ?array &$dbLog = null): void
    {
        try {
            $order->loadMissing(['user', 'orderDetails.products']);

            $phone = $order->user->phone ?? $order->mobile ?? null;

            if (empty($phone)) {
                if ($dbLog !== null)
                    $this->logToDB($dbLog, "WhatsApp: No phone number found for order #{$order->id}", 'warning', 'WHATSAPP');
                return;
            }

            // Build order items summary
            $itemsSummary = '';
            if ($order->orderDetails->isNotEmpty()) {
                $itemsSummary = "\n\n📦 تفاصيل الطلب:\n";
                foreach ($order->orderDetails as $detail) {
                    $productName = optional($detail->products)->name ?? 'منتج';
                    $qty = $detail->amout ?? 1;
                    $itemsSummary .= "- {$productName} (x{$qty})\n";
                }
                $itemsSummary .= "الإجمالي: " . ($order->total ?? $order->amount ?? '0') . " ج.م";
            }

            $statusMessages = [
                'success'   => 'تم الدفع بنجاح 💳✅' . "\n" . 'طلبك رقم #' . $order->id . ' تم تأكيده وجاري تجهيزه للشحن.',
                'reserved'  => 'تم الدفع بنجاح 💳✅' . "\n" . 'طلبك رقم #' . $order->id . ' تم حجزه وسيتم التواصل معك قريباً.',
                'cancelled' => 'تم إلغاء طلبك رقم #' . $order->id . ' بسبب عدم اكتمال الدفع. إذا كان هناك خطأ تواصل معنا.',
            ];

            $message = $statusMessages[$status] ?? 'تم تحديث حالة طلبك رقم #' . $order->id . ' إلى: ' . $status;
            $message = "مرحباً " . ($order->name ?? 'عميلنا العزيز') . " 👋\n\n" . $message . $itemsSummary . "\n\nشكراً لتعاملك مع هاي اكاديمي ستور 📚";

            $whatsapp = new WhatsappService();
            $result = $whatsapp->send($phone, $message);

            if ($dbLog !== null) {
                $sent = ($result['status'] ?? false) ? 'Success' : 'Failed';
                $this->logToDB($dbLog, "WhatsApp sent to {$phone} - Status: {$sent}", 'info', 'WHATSAPP', [
                    'phone'  => $phone,
                    'status' => $sent,
                ]);
            }
        } catch (\Exception $e) {
            if ($dbLog !== null) {
                $this->logToDB($dbLog, 'WhatsApp ERROR: ' . $e->getMessage(), 'error', 'WHATSAPP');
            }
            Log::error('WhatsApp order notification failed', [
                'order_id' => $order->id,
                'status'   => $status,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}
