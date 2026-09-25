<?php

namespace App\Http\Controllers\Admin;

use Mpdf\Mpdf;
use App\Models\Order;
use App\Mail\delivery;
use App\Models\Product;
use App\Mail\SuccessPaid;
use App\Traits\ImageTrait;
use App\Traits\DeleteTrait;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\ShippingMethod;
use App\Services\SliderService;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BarcodeOrdersExport;
use App\Models\User;
use App\Models\OrderDetail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class OrderController extends Controller
{
    use ImageTrait, DeleteTrait, GeneralTrait;

    protected $sliderService;

    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    public function index()
    {
        $shippingMethods = ShippingMethod::all();
        $reversable_books = Product::where('state', '2')->get();
        return view('dashboard.pages.order.index', compact('shippingMethods', 'reversable_books'));
    }

    public function barcodeOrders()
    {
        return view('dashboard.pages.barcode.orders');
    }

    public function excelExport(Request $request)
    {
        $status = $request->query('status');
        $limit = $request->query('limit');
        $shipping = $request->query('shipping');
        $bookId = $request->query('book_id');
        $filename = 'orders-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(
            new BarcodeOrdersExport($status, $limit, $shipping, $bookId),
            $filename
        );
    }

    public function orderbarcode()
    {
        return view('dashboard.pages.barcode.index');
    }

    public function datatable(Request $request)
    {
        $query = Order::with(['user', 'shipping'])
            ->select('orders.*')
            ->orderBy('id', 'DESC');

        if ($request->has('state') && !empty($request->state)) {
            $query->where('status', $request->state);
        }
        if ($request->filled('shipping') && $request->shipping !== 'all') {
            $query->whereHas('shipping', function ($q) use ($request) {
                $q->where('type', $request->shipping);
            });
        }



        return DataTables::of($query)
            ->editColumn('name', function ($row) {
                return $row->name ?? ($row->user->name ?? 'N/A');
            })
            ->editColumn('phone', function ($row) {
                return $row->mobile;
            })
            ->editColumn('temp_mobile', function ($row) {
                return $row->temp_mobile;
            })
            ->editColumn('email', function ($row) {
                return $row->user->email ?? '';
            })
            ->editColumn('address', function ($row) {
                return $row->address ?? '';
            })
            ->addColumn('details', function ($row) {
                return '<a href="' . route('dashboard.orders.details', $row->id) . '" type="button" class="btn btn-lg btn-block btn-success lift text-uppercase p-3 ">تفاصيل</a>';
            })
            ->addColumn('edit_order', function ($row) {
                return '<a href="' . route('dashboard.editOrder', $row->id) . '" type="button" class="btn btn-lg btn-block btn-danger lift text-uppercase p-3 ">تعديل الطلب</a>';
            })
            ->addColumn('change_status', function ($row) {
                $dropdown = '<select class="form-control change-status" data-order-id="' . $row->id . '" onchange="handleStatusChange(this)">';
                $dropdown .= '<option value="" disabled selected>تغيير الحالة</option>';
                $statuses = [
                    'new' => 'طلب جديد',
                    'pending' => 'طلب معلق',
                    'success' => 'طلب ناجح',
                    'cancelled' => 'طلب ملغي',
                    'reserved' => 'طلب محجوز'
                ];
                foreach ($statuses as $status => $statusText) {
                    $dropdown .= '<option value="' . route('dashboard.changeStatus', ['id' => $row->id, 'status' => $status]) . '">' . $statusText . '</option>';
                }
                $dropdown .= '</select>';
                return $dropdown;
            })
            ->editColumn('method', function ($row) {
                return $row->method ?? '';
            })
            ->addColumn('account', function ($row) {
                return $row->account;
            })
            ->addColumn('image', function ($row) {
                if ($row->image) {
                    $link = asset('storage/images/screens/' . $row->image);
                    return "<a href='" . $link . "' target='_blank'>عرض الصورة</a>";
                }
                return "لا يوجد";
            })
            ->addColumn('barcode', function ($row) {
                return $row->barcode;
            })
            ->addColumn('addbarcode', function ($row) {
                return '<a href=' . route('dashboard.orders.editbarcode', $row->id) . ' type="button" class="btn btn-sm btn-block btn-success lift text-uppercase">أضافه الباركود</a>';
            })
            ->addColumn('admin_addbarcode', function ($row) {
                // Don't show barcode button for branch orders
                if (in_array($row->shipping_method, ['3', '4'])) {
                    return '—';
                }
                $shipping = request('shipping') ? '?shipping=' . request('shipping') : '';
                return '<a href=' . route('dashboard.orders.editbarcode', $row->id) . $shipping . ' type="button" class="btn btn-sm btn-block btn-success lift text-uppercase">أضافه الباركود</a>';
            })
            ->addColumn('shipping_method', function ($row) {
                if ($method = $row->shipping) {
                    return e($method->name);
                }
                if ($row->shipping_name) {
                    return e($row->shipping_name);
                }
                if ($row->shipping_method && is_numeric($row->shipping_method)) {
                    $found = \App\Models\ShippingMethod::find($row->shipping_method);
                    if ($found) {
                        return e($found->name);
                    }
                }
                return '—';
            })

            ->addColumn('state', function ($row) {
                switch ($row->status) {
                    case "new":
                        return "<h2 class='badge bg-warning text-dark'>طلب جديد</h2>";
                    case "success":
                        return "<h2 class='badge bg-success'>طلب ناجح</h2>";
                    case "cancelled":
                        return "<h2 class='badge bg-danger'>طلب ملغي</h2>";
                    case "pending":
                        return "<h2 class='badge bg-info'>طلب معلق</h2>";
                    case "reserved":
                        return "<h2 class='badge bg-primary'>طلب محجوز</h2>";
                    default:
                        return '';
                }
            })
            ->addColumn('tracker_state', function ($row) {
                switch ($row->tracker) {
                    case "delivered":
                        return "<span class='badge bg-success fs-6'>تم الاشعار</span>";
                    case "shipped":
                        return "<span class='badge bg-info fs-6'>تم تاكيد الطلب</span>";
                    case "processing":
                        return "<span class='badge bg-warning fs-6'>قيد المعالجة</span>";
                    case null:
                    case "":
                        return "<span class='badge bg-secondary fs-6'>لم يتم التحديد</span>";
                    case "pending":
                        return "<span class='badge bg-secondary fs-6'>تم تاكيد الطلب</span>";
                    default:
                        return "<span class='badge bg-primary fs-6'>" . $row->tracker . "</span>";
                }
            })
            ->addColumn('branch_actions', function ($row) {
                if (!in_array($row->shipping_method, ['3', '4'])) {
                    return '—';
                }

                $tracker = $row->tracker ?? 'pending';
                $detailsBtn = '<a href="' . route('dashboard.orders.details', $row->id) . '" class="btn btn-sm btn-info">
                    <i class="fa fa-eye"></i> التفاصيل
                </a>';

                // Notification button color based on tracker state
                $notifyBtnClass = '';
                $notifyText = '';
                $disabled = '';

                switch ($tracker) {
                    case 'delivered':
                        $notifyBtnClass = 'btn-success';
                        $notifyText = '<i class="fa fa-check"></i> تم الإشعار';
                        $disabled = 'disabled';
                        break;
                    case 'shipped':
                        $notifyBtnClass = 'btn-warning';
                        $notifyText = '<i class="fa fa-bell"></i> إرسال إشعار';
                        break;
                    case 'pending':
                        $notifyBtnClass = 'btn-warning';
                        $notifyText = '<i class="fa fa-bell"></i> إرسال إشعار';
                        break;
                    default:
                        $notifyBtnClass = 'btn-secondary';
                        $notifyText = '<i class="fa fa-clock"></i> في الانتظار';
                        $disabled = 'disabled';
                        break;
                }

                $notifyBtn = '<button class="btn btn-sm ' . $notifyBtnClass . ' send-notification"
                    data-order-id="' . $row->id . '" ' . $disabled . '>
                    ' . $notifyText . '
                </button>';

                return '<div class="d-flex gap-1">' . $detailsBtn . $notifyBtn . '</div>';
            })
            ->rawColumns(['details', 'edit_order', 'change_status', 'image', 'state', 'addbarcode', 'admin_addbarcode', 'tracker_state', 'branch_actions'])
            ->toJson();
    }



    public function details($id)
    {
        $order = Order::with('shipping')->findOrFail($id);
        return view('dashboard.pages.order.details', compact('order'));
    }

    public function changestate(Request $request)
    {
        $state = $request->state;
        $orderid = $request->id;
        $order = Order::findOrFail($orderid);
        $whatsappStatus = null;
        try {
            DB::beginTransaction();
            if ($state == "1") {
                $order->status = "success";
                $order->is_paid = "1";
                $order->tracker = "shipped"; // second stage
                $details = [
                    'id' => $order->id,
                    'name' => $order->name,
                    'shipping' => $order->shipping,
                ];
                $order->load(['shipping', 'orderDetails.products']);

                // Send success email only if customer email exists
                if ($order->user && !empty($order->user->email)) {
                    try {
                        Mail::to($order->user->email)->send(new SuccessPaid($order));
                    } catch (\Exception $mailEx) {
                        // Do not fail the whole state change due to mail issues
                        // Optionally log if needed
                    }
                }

                $whatsappStatus = 'success';
            } elseif ($state == "2") {
                $order->status = "cancelled";
                foreach ($order->orderDetails as $detail) {
                    $product = Product::find($detail->product_id);
                    $product->quantity = $product->quantity + $detail->amout;
                    if ($product->state == 0) {
                        // $product->state = 1;
                    }
                    $product->save();
                }

                $whatsappStatus = 'cancelled';
            }
            $order->save();
            DB::commit();

            // Send WhatsApp after successful commit
            if ($whatsappStatus) {
                $this->sendOrderStatusWhatsapp($order, $whatsappStatus);
            }

            return response()->json([
                "success" => true,
                'code' => 200,
                'msg' => "تم تنفيذ الاجراء بنجاح"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'code' => 400,
                'msg' => "خطأ اثناء التنفيذ"
            ], 400);
        }
    }

    public function editbarcode($id)
    {
        $order = Order::find($id);
        return view('dashboard.pages.barcode.edit', compact('order'));
    }

    public function admineditbarcode($id)
    {
        $order = Order::find($id);
        return view('dashboard.pages.barcode.edit', compact('order'));
    }

    public function addbarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
            'id' => 'required|exists:orders,id'
        ], [
            'barcode.required' => 'حقل الباركود مطلوب',
            'barcode.string' => 'الباركود يجب أن يكون نص',
            'id.required' => 'معرف الطلب مطلوب',
            'id.exists' => 'الطلب غير موجود'
        ]);

        $barcode = $request->barcode;
        $orderid = $request->id;
        $order = Order::find($orderid);
        try {
            DB::beginTransaction();

            $order->barcode = $barcode;
            $order->tracker = "delivered"; // third stage
            $order->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                'code' => 400,
                'info' => $e->getMessage(),
                'msg' => "خطأ اثناء التنفيذ"
            ], 400);
        }

        // Send email & WhatsApp outside the DB transaction so failures don't block saving
        try {
            $shippingMethod = ShippingMethod::find($order->shipping_method);

            $details = [
                'id' => $order->id,
                'name' => $order->name,
                'shipping' => $shippingMethod,
                'barcode' => $order->barcode
            ];

            Mail::to($order->user->email)->send(new delivery($details));
        } catch (\Exception $e) {
            // Mail failed (e.g. rate limit) — barcode is already saved
        }

        try {
            $this->sendOrderStatusWhatsapp($order, 'shipped');
        } catch (\Exception $e) {
            // WhatsApp failed — barcode is already saved
        }

        return response()->json([
            "success" => true,
            'code' => 200,
            'msg' => "تم تحديث الباركود بنجاح"
        ], 200);
    }


    public function export(Request $request)
    {
        $limit = $request->query('limit', 10);
        $status = $request->query('status');
        $shipping = $request->query('shipping');
        $bookId = $request->query('book_id');

        $query = Order::with('shipping')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($shipping) {
            $query->where('shipping_method', $shipping);
        }

        if ($bookId) {
            $query->whereHas('orderDetails', function ($q) use ($bookId) {
                $q->where('product_id', $bookId);
            });
        }

        $orders = $query->limit($limit)->get();

        $mpdf = new Mpdf([
            'default_font' => 'DejaVu Sans',
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'format' => 'A4'
        ]);
        $html = view('dashboard.pages.order.export', compact('orders'))->render();

        $mpdf->WriteHTML($html);
        $mpdf->Output('orders.pdf', 'D');
        exit;
    }

    public function successExport(Request $request)
    {
        ini_set('pcre.backtrack_limit', 10000000);

        $limit = $request->query('limit', 10);
        $status = $request->query('status');
        $shipping = $request->query('shipping');
        $bookId = $request->query('book_id');

        $query = Order::with(['orderDetails.products', 'shipping'])
            ->whereDoesntHave('shipping', function ($q) {
                $q->where('type', 'branch');
            })
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($shipping) {
            $query->where('shipping_method', $shipping);
        }

        if ($bookId) {
            $query->whereHas('orderDetails', function ($q) use ($bookId) {
                $q->where('product_id', $bookId);
            });
        }

        $orders = $query->limit($limit)->get();

        $mpdf = new Mpdf([
            'default_font' => 'DejaVu Sans',
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'format' => 'A4',
        ]);

        $html = view('dashboard.pages.order.successExport', compact('orders'))->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output('success-orders.pdf', 'D');
        exit;
    }

    public function groupedExport(Request $request)
    {
        ini_set('pcre.backtrack_limit', 10000000);
        $limit = $request->query('limit', 10);
        $status = $request->query('status');
        $shipping = $request->query('shipping');
        $bookId = $request->query('book_id');

        $query = Order::with('shipping')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($shipping) {
            $query->where('shipping_method', $shipping);
        }

        if ($bookId) {
            $query->whereHas('orderDetails', function ($q) use ($bookId) {
                $q->where('product_id', $bookId);
            });
        }

        $orders = $query->limit($limit)->get();

        $mpdf = new Mpdf([
            'default_font' => 'DejaVu Sans',
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'format' => 'A4'
        ]);
        $html = view('dashboard.pages.order.groupedExport', compact('orders'))->render();

        $mpdf->WriteHTML($html);
        $mpdf->Output('grouped-orders.pdf', 'D');
        exit;
    }


    public function branchExport(Request $request)
    {
        ini_set('pcre.backtrack_limit', 10000000);

        $limit = $request->query('limit', 10);
        $status = $request->query('status');
        $shipping = $request->query('shipping');
        $bookId = $request->query('book_id');

        $query = Order::with(['orderDetails.products', 'shipping'])
            ->whereHas('shipping', function ($q) {
                $q->where('type', 'branch');
            })
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($shipping) {
            $query->where('shipping_method', $shipping);
        }

        if ($bookId) {
            $query->whereHas('orderDetails', function ($q) use ($bookId) {
                $q->where('product_id', $bookId);
            });
        }

        $orders = $query->limit($limit)->get();

        $mpdf = new Mpdf([
            'default_font' => 'DejaVu Sans',
            'mode' => 'utf-8',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'format' => 'A4',
        ]);

        $html = view('dashboard.pages.order.export', compact('orders'))->render();
        $mpdf->WriteHTML($html);
        $mpdf->Output('branch-orders.pdf', 'D');
        exit;
    }

    public function changeStatus($order_id, $status)
    {
        $order = Order::find($order_id);
        switch ($status) {
            case 'success':
                $order->update(['status' => 'success']);
                break;
            case 'reserved':
                $order->update(['status' => 'reserved']);
                break;
            case 'pending':
                $order->update(['status' => 'pending']);
                break;
            case 'cancelled':
                $order->update(['status' => 'cancelled']);
                break;
            default:
                $order->update(['status' => 'Not Found']);
        }

        $this->sendOrderStatusWhatsapp($order, $status);

        return redirect()->to(route('dashboard.orders'));
    }

    //    public function changeSuccessStatus($order_id)
    //    {
    //        $order = Order::find($order_id);
    //        $order->update(['status' => 'success']);
    //        return redirect()->to(route('dashboard.orders'));
    //    }

    public function updateOrder(Request $request, $order_id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string',
            'address' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'near_post' => 'nullable|string|max:255',
            'shipping_method' => 'required|exists:shipping_methods,id',
        ]);

        $order = Order::findOrFail($order_id);
        $order->fill($data);

        // overwrite name/address from the shipping method
        $method = \App\Models\ShippingMethod::find($data['shipping_method']);
        $order->shipping_method = $method->id;
        $order->shipping_name = $method->name;
        $order->shipping_address = $method->address;

        $order->save();

        return redirect()
            ->route('dashboard.orders')
            ->with('success', 'تم تعديل الطلب بنجاح');
    }



    public function editOrder($order_id)
    {

        $order = Order::findOrFail($order_id);
        $shippingMethods = ShippingMethod::all();
        $products = Product::all();

        return view('dashboard.pages.order.edit', compact('order', 'shippingMethods', 'products'));
    }

    public function updateBook(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        if ($request->has('remove')) {
            $detailId = $request->input('remove');
            $order->orderDetails()->where('id', $detailId)->delete();
        }
        // 🔹 Update existing items
        if ($request->has('items')) {
            foreach ($request->items as $itemData) {
                $detail = $order->orderDetails()->where('id', $itemData['id'])->first();
                if ($detail) {
                    $detail->amout = $itemData['amount'];
                    $detail->total_price = $detail->price * $itemData['amount'];
                    $detail->save();
                }
            }
        }
        // 🔹 Add a new product
        if ($request->has('new_item') && !empty($request->new_item['product_id'])) {
            $productId = $request->new_item['product_id'];
            $amount = $request->new_item['amount'] ?? 1;

            $product = Product::find($productId);
            if ($product) {
                $order->orderDetails()->create([
                    'product_id' => $product->id,
                    'price' => $product->price, // assume product has price column
                    'amout' => $amount,
                    'total_price' => $product->price * $amount,
                ]);
            }
        }

        // 🔹 Recalculate order total
        $orderAmount = $order->orderDetails()->sum('total_price');

        $order->amount = $orderAmount;

        $order->total = $orderAmount + $order->delivery_fee;

        $order->save();

        return redirect()->back()->with('success', 'تم تحديث الطلب بنجاح ✨');
    }
    public function update_all_reversed_order(Request $request): \Illuminate\Http\RedirectResponse
    {
        $query = Order::query()->where('status', '=', 'reserved');

        // Filter by book if book_id is provided
        if ($request->has('book_id') && !empty($request->book_id)) {
            $query->whereHas('orderDetails', function ($q) use ($request) {
                $q->where('product_id', $request->book_id);
            });
        }

        $orders = $query->get();

        foreach ($orders as $order) {
            $order->update(['status' => 'success']);
        }

        return redirect()->to(route('dashboard.orders'));
    }

    public function sendBranchNotification(Request $request)
    {
        $request->validate([
            'custom_message' => 'required|string|max:1000'
        ], [
            'custom_message.required' => 'الرسالة المخصصة مطلوبة',
            'custom_message.max' => 'الرسالة لا يجب أن تتجاوز 1000 حرف'
        ]);

        $customMessage = $request->input('custom_message');

        // Get branch orders that are ready but not yet delivered - optimized single query
        $orders = Order::with(['user'])
            ->whereIn('shipping_method', ['3', '4'])
            ->where('status', 'success') // Only ready orders
            ->where(function ($q) {
                $q->whereNull('tracker')
                    ->orWhere('tracker', '')
                    ->orWhere('tracker', '!=', 'delivered');
            })
            ->orderBy('id', 'desc') // Get newest orders first
            ->limit(10) // Send in batches
            ->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'لا يوجد طلبات جاهزة للإشعار ✅'
            ]);
        }

        $sentCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($orders as $order) {
            if ($order->user && $order->user->email) {
                try {
                    // Send email notification
                    Mail::to($order->user->email)->send(new \App\Mail\BranchNotification($order, $customMessage));

                    // Don't update tracker - keep original state
                    $sentCount++;

                    // Add delay to respect rate limits
                    if ($sentCount < $orders->count()) {
                        sleep(2); // 2 seconds delay between emails
                    }
                } catch (\Exception $e) {
                    $failedCount++;
                    $errors[] = "Order #{$order->id}: " . $e->getMessage();
                }
            } else {
                $failedCount++;
                $errors[] = "Order #{$order->id}: No email address";
            }
        }

        $remainingOrders = Order::whereIn('shipping_method', ['3', '4'])
            ->where('status', 'success')
            ->where(function ($q) {
                $q->whereNull('tracker')
                    ->orWhere('tracker', '')
                    ->orWhere('tracker', '!=', 'delivered');
            })
            ->count();

        $message = "تم إرسال {$sentCount} إشعار بنجاح.";

        if ($failedCount > 0) {
            $message .= " فشل في إرسال {$failedCount} إشعار.";
        }

        $message .= " يتبقى {$remainingOrders} طلب.";

        return response()->json([
            'success' => $sentCount > 0,
            'message' => $message,
            'errors' => $errors,
            'sent_count' => $sentCount,
            'failed_count' => $failedCount,
            'remaining_count' => $remainingOrders
        ]);
    }

    public function sendIndividualNotification(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'custom_message' => 'required|string|max:1000'
        ], [
            'order_id.required' => 'رقم الطلب مطلوب',
            'order_id.exists' => 'الطلب غير موجود',
            'custom_message.required' => 'الرسالة المخصصة مطلوبة',
            'custom_message.max' => 'الرسالة لا يجب أن تتجاوز 1000 حرف'
        ]);

        $order = Order::with(['user'])->findOrFail($request->order_id);

        // Check if it's a branch order
        if (!in_array($order->shipping_method, ['3', '4'])) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الطلب ليس طلب فرع'
            ], 400);
        }

        // Check if order is ready for notification
        if ($order->status !== 'success') {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير جاهز للإشعار'
            ], 400);
        }

        // Check if already notified
        if ($order->tracker === 'delivered') {
            return response()->json([
                'success' => false,
                'message' => 'تم إرسال الإشعار لهذا الطلب مسبقاً'
            ], 400);
        }

        if (!$order->user || !$order->user->email) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد بريد إلكتروني للعميل'
            ], 400);
        }

        try {
            // Send email notification
            Mail::to($order->user->email)->send(new \App\Mail\BranchNotification($order, $request->custom_message));

            // Update tracker to delivered after successful email sending
            $order->update(['tracker' => 'delivered']);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الإشعار بنجاح للطلب #' . $order->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال الإشعار: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send WhatsApp notification to customer on order status change
     */
    private function sendOrderStatusWhatsapp(Order $order, string $status): void
    {
        try {
            $order->loadMissing(['user', 'orderDetails.products']);

            $phone = $order->user->phone ?? $order->mobile ?? null;

            if (empty($phone)) {
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
                'new' => 'تم استلام طلبك الجديد رقم #' . $order->id . ' وجاري مراجعته.',
                'pending' => 'طلبك رقم #' . $order->id . ' قيد المراجعة حالياً.',
                'success' => 'تم تأكيد طلبك رقم #' . $order->id . ' بنجاح ✅ وجاري تجهيزه للشحن.',
                'cancelled' => 'تم إلغاء طلبك رقم #' . $order->id . '. إذا كان هناك خطأ تواصل معنا.',
                'reserved' => 'طلبك رقم #' . $order->id . ' تم حجزه وسيتم التواصل معك قريباً.',
                'shipped' => 'طلبك رقم #' . $order->id . ' تم شحنه ✅ وفي الطريق إليك.' . (!empty($order->barcode) ? "\n\nكود التتبع: " . $order->barcode . "\n\nيمكنك تتبع شحنتك من هنا:\nhttps://egyptpost.gov.eg/ar-eg/home/eservices/track-and-trace/" : ''),
                'delivered' => 'طلبك رقم #' . $order->id . ' تم تسليمه بنجاح 🎉',
            ];

            $message = $statusMessages[$status] ?? 'تم تحديث حالة طلبك رقم #' . $order->id . ' إلى: ' . $status;

            $message = "مرحباً " . ($order->name ?? 'عميلنا العزيز') . " 👋\n\n" . $message . $itemsSummary . "\n\nشكراً لتعاملك مع هاي اكاديمي ستور 📚";

            $whatsapp = new WhatsappService();
            $whatsapp->send($phone, $message);
        } catch (\Exception $e) {
            Log::error('WhatsApp order notification failed', [
                'order_id' => $order->id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Show the library booking form for creating an in-person order and viewing statistics.
     */
    public function createLibraryOrder(Request $request)
    {
        $admin = auth('admin')->user();
        if ($admin && !$admin->hasRole('Super Admin') && !$admin->can('create_library_orders') && !$admin->can('view_orders')) {
            abort(403, 'غير مصرح لك بالوصول لصفحة الحجز من المكتبة');
        }

        // Fetch ONLY products that can be booked/pre-ordered (state = 2: يمكن حجزه)
        $products = Product::where('is_deleted', 0)
            ->where('state', '2')
            ->with(['translations', 'brands', 'sliders', 'category'])
            ->orderBy('id', 'desc')
            ->get();

        // Fetch all products for the statistics filter dropdown
        $allProducts = Product::where('is_deleted', 0)
            ->with(['translations', 'brands'])
            ->orderBy('id', 'desc')
            ->get();

        // Fetch ONLY library branches (type = branch)
        $shippingMethods = ShippingMethod::where('type', 'branch')->get();
        if ($shippingMethods->isEmpty()) {
            $shippingMethods = ShippingMethod::all();
        }

        // Calculate initial statistics
        $stats = $this->calculateLibraryBookingStats($request);
        $activeTab = $request->query('tab', 'booking');

        return view('dashboard.pages.order.library_booking', compact('products', 'allProducts', 'shippingMethods', 'stats', 'activeTab'));
    }

    /**
     * Store a library booking order created by the admin.
     */
    public function storeLibraryOrder(Request $request)
    {
        $admin = auth('admin')->user();
        if ($admin && !$admin->hasRole('Super Admin') && !$admin->can('create_library_orders')) {
            abort(403, 'غير مصرح لك بإنشاء طلبات الحجز من المكتبة');
        }

        $request->validate([
            'student_name' => 'required|string|max:191',
            'mobile' => ['required', 'string', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'temp_mobile' => ['nullable', 'string', 'regex:/^(010|011|012|015)[0-9]{8}$/'],
            'shipping_method_id' => 'required|exists:shipping_methods,id',
            'payment_type' => 'required|in:full,deposit,later',
            'deposit_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:success,reserved,new,pending',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'nullable|numeric|min:0',
        ], [
            'student_name.required' => 'اسم الطالب مطلوب',
            'mobile.required' => 'رقم هاتف الطالب مطلوب',
            'mobile.regex' => 'رقم الهاتف يجب أن يكون رقم مصري صحيح مكون من 11 رقم (010/011/012/015)',
            'temp_mobile.regex' => 'رقم الهاتف الإضافي يجب أن يكون رقم مصري صحيح مكون من 11 رقم',
            'shipping_method_id.required' => 'يرجى اختيار فرع الاستلام بالمكتبة',
            'items.required' => 'يجب اختيار كتاب واحد على الأقل',
            'items.min' => 'يجب اختيار كتاب واحد على الأقل',
        ]);

        return DB::transaction(function () use ($request) {
            $mobile = $request->mobile;

            // 1. Find or create the user account for the student
            $user = User::where('phone', $mobile)
                ->orWhere('email', $mobile . '@student.library')
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $request->student_name,
                    'phone' => $mobile,
                    'email' => $mobile . '@student.library',
                    'password' => Hash::make($mobile),
                    'address' => $request->notes ?? 'حجز من المكتبة',
                ]);
            }

            // 2. Shipping method (Library Branch pickup - always 0 delivery fee)
            $shippingMethod = ShippingMethod::find($request->shipping_method_id);
            if (!$shippingMethod) {
                $shippingMethod = ShippingMethod::where('type', 'branch')->first() ?? ShippingMethod::first();
            }

            $deliveryFee = 0;

            // 3. Process products & calculate totals
            $amount = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $qty = (int) $item['quantity'];
                $availableStock = (int) ($product->quantity ?? 0);
                $prodTitle = $product->short_name ?: $product->name;

                if ($availableStock <= 0) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => ["عفواً، الكتاب '{$prodTitle}' نفد من المخزن (الكمية المتاحة: 0) ولا يمكن إتمام الحجز به."],
                    ]);
                }

                if ($qty > $availableStock) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => ["الكمية المطلوبة من كتاب '{$prodTitle}' ({$qty} نسخة) تتجاوز الرصيد المتاح بالمخزن ({$availableStock} نسخة فقط)."],
                    ]);
                }
                
                $unitPrice = isset($item['price']) && $item['price'] !== '' && is_numeric($item['price'])
                    ? (float) $item['price']
                    : (float) ($product->final_price ?? $product->price ?? 0);

                $itemTotal = $unitPrice * $qty;
                $amount += $itemTotal;

                $itemsData[] = [
                    'product' => $product,
                    'quantity' => $qty,
                    'price' => $unitPrice,
                    'total_price' => $itemTotal,
                ];
            }

            $discount = (float) ($request->discount ?? 0);
            $total = max(0, ($amount + $deliveryFee) - $discount);

            // 4. Handle Payment Type (Full Cash, Deposit, or Later on delivery)
            $paymentType = $request->payment_type ?? 'full';
            $paidAmount = 0;
            $remainingAmount = 0;
            $isPaid = 0;
            $orderStatus = $request->status ?: 'reserved'; // الحالة الافتراضية دائماً "طلب محجوز"
            $methodString = 'كاش بالمكتبة';

            if ($paymentType === 'full') {
                $paidAmount = $total;
                $remainingAmount = 0;
                $isPaid = 1;
                $paymentInfo = "مدفوع كاش بالكامل ({$total} ج.م)";
            } elseif ($paymentType === 'deposit') {
                $paidAmount = min($total, max(0, (float) ($request->deposit_amount ?? 0)));
                $remainingAmount = max(0, $total - $paidAmount);
                $isPaid = ($remainingAmount <= 0) ? 1 : 0;
                $methodString = 'كاش بالمكتبة (عربون)';
                $paymentInfo = "مدفوع عربون: {$paidAmount} ج.م | متبقي عند الاستلام: {$remainingAmount} ج.م";
            } else {
                // later / no deposit
                $paidAmount = 0;
                $remainingAmount = $total;
                $isPaid = 0;
                $methodString = 'كاش عند الاستلام بالمكتبة';
                $paymentInfo = "حجز - الدفع بالكامل عند الاستلام ({$total} ج.م)";
            }

            // Combine admin notes with deposit/payment summary
            $notes = $request->notes ? trim($request->notes) . " | " . $paymentInfo : $paymentInfo;

            // 5. Generate unique order code
            $code = '#' . Str::upper(Str::random(8));
            while (Order::where('code', $code)->exists()) {
                $code = '#' . Str::upper(Str::random(8));
            }

            $shippingName = $shippingMethod ? $shippingMethod->name : 'استلام من المكتبة';
            $shippingAddress = $shippingMethod ? ($shippingMethod->address ?? $shippingMethod->name) : 'المكتبة';

            // 6. Create Order (Exact format as website branch booking)
            $order = Order::create([
                'user_id' => $user->id,
                'name' => $request->student_name,
                'mobile' => $mobile,
                'temp_mobile' => $request->temp_mobile,
                'address' => $shippingName, // مثل الويبسايت تماماً: اسم الفرع
                'address2' => $notes,
                'near_post' => null, // حجز مكتبة لا يوجد به مكتب بريد
                'governorate_id' => $shippingMethod?->government ?? null,
                'date' => now(),
                'status' => $orderStatus,
                'is_paid' => $isPaid,
                'code' => $code,
                'amount' => $amount,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'shipping_method' => (string) ($shippingMethod?->id),
                'shipping_method_id' => $shippingMethod?->id,
                'shipping_name' => $shippingName,
                'shipping_address' => $shippingAddress,
                'method' => $methodString,
                'tracker' => 'pending',
            ]);

            // 6. Create Order Details & update inventory
            foreach ($itemsData as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'amout' => $item['quantity'],
                    'price' => $item['price'],
                    'total_price' => $item['total_price'],
                ]);

                // Deduct inventory if stock is tracked
                $prod = $item['product'];
                if ($prod->quantity !== null) {
                    $prod->quantity = max(0, $prod->quantity - $item['quantity']);
                    if ($prod->state != 2) {
                        $prod->state = ($prod->quantity > 0) ? 1 : 0;
                    }
                    $prod->save();
                }
            }

            // Optional WhatsApp notification
            try {
                $this->sendOrderStatusWhatsapp($order, $order->status);
            } catch (\Exception $e) {
                Log::warning('WhatsApp notification failed for library booking', ['error' => $e->getMessage()]);
            }

            return redirect()->route('dashboard.orders.details', $order->id)
                ->with('success', "تم إنشاء حجز المكتبة بنجاح برقم الطلب #{$order->id} ({$order->code})");
        });
    }

    /**
     * AJAX endpoint to fetch statistics data and rendered HTML for library orders.
     */
    public function libraryBookingStatistics(Request $request)
    {
        $admin = auth('admin')->user();
        if ($admin && !$admin->hasRole('Super Admin') && !$admin->can('create_library_orders') && !$admin->can('view_orders')) {
            return response()->json(['error' => 'غير مصرح لك بالوصول للإحصائيات'], 403);
        }

        $stats = $this->calculateLibraryBookingStats($request);

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('dashboard.pages.order.partials.library_stats_content', compact('stats'))->render();
            return response()->json([
                'status' => 'success',
                'stats' => $stats,
                'html' => $html,
            ]);
        }

        return redirect()->route('dashboard.orders.library_booking', ['tab' => 'statistics'] + $request->all());
    }

    /**
     * Export library booking statistics to CSV (with Arabic UTF-8 BOM for Excel).
     */
    public function libraryBookingStatisticsExport(Request $request)
    {
        $admin = auth('admin')->user();
        if ($admin && !$admin->hasRole('Super Admin') && !$admin->can('create_library_orders') && !$admin->can('view_orders')) {
            abort(403, 'غير مصرح لك بتصدير الإحصائيات');
        }

        $stats = $this->calculateLibraryBookingStats($request);
        $filename = 'library-sales-stats-' . $stats['period'] . '-' . date('Y-m-d') . '.csv';

        $callback = function () use ($stats) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel

            fputcsv($handle, ['تقرير إحصائيات مبيعات فروع المكتبة']);
            fputcsv($handle, ['الدورة / الفترة:', $stats['period_label']]);
            fputcsv($handle, ['من تاريخ:', $stats['from_date'], 'إلى تاريخ:', $stats['to_date']]);
            fputcsv($handle, []);

            fputcsv($handle, ['إجمالي الكتب المباعة:', $stats['grand_total_books']]);
            fputcsv($handle, ['إجمالي المبيعات (جنيه):', number_format($stats['grand_total_revenue'], 2)]);
            fputcsv($handle, ['إجمالي عدد الطلبات:', $stats['grand_total_orders']]);
            fputcsv($handle, ['أكثر الفروع مبيعاً:', $stats['top_branch']]);
            fputcsv($handle, ['أكثر الكتب مبيعاً:', $stats['top_product']]);
            fputcsv($handle, []);

            fputcsv($handle, ['=== تفاصيل مبيعات كل فرع والأصناف ===']);
            fputcsv($handle, ['الفرع', 'اسم الكتاب / الصنف', 'المدرس / المؤلف', 'سعر الوحدة', 'الكمية المباعة', 'إجمالي المبلغ']);

            foreach ($stats['branches_stats'] as $branch) {
                if (empty($branch['items'])) {
                    fputcsv($handle, [$branch['branch_name'], 'لا توجد مبيعات', '—', 0, 0, 0]);
                } else {
                    foreach ($branch['items'] as $item) {
                        fputcsv($handle, [
                            $branch['branch_name'],
                            $item['product_name'],
                            $item['author'],
                            $item['unit_price'],
                            $item['quantity'],
                            $item['total_amount']
                        ]);
                    }
                }
                fputcsv($handle, [
                    'إجمالي ' . $branch['branch_name'],
                    '',
                    '',
                    '',
                    $branch['total_books'],
                    $branch['total_revenue']
                ]);
                fputcsv($handle, []);
            }

            fputcsv($handle, ['=== ملخص مبيعات الأصناف عبر جميع الفروع ===']);
            fputcsv($handle, ['اسم الكتاب / الصنف', 'المدرس / المؤلف', 'سعر الوحدة', 'إجمالي الكمية المباعة', 'إجمالي المبيعات']);
            foreach ($stats['products_summary'] as $prod) {
                fputcsv($handle, [
                    $prod['product_name'],
                    $prod['brand_name'],
                    $prod['unit_price'],
                    $prod['total_quantity'],
                    $prod['total_revenue']
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Calculate comprehensive statistics for library branch sales and book items.
     */
    protected function calculateLibraryBookingStats(Request $request)
    {
        $period = $request->input('period', 'month');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $selectedBranchId = $request->input('branch_id');
        $selectedProductId = $request->input('product_id');
        $selectedStatus = $request->input('status', 'all');

        $now = Carbon::now();
        $startDate = null;
        $endDate = null;
        $periodLabel = '';

        switch ($period) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $periodLabel = 'اليوم (' . $now->format('Y-m-d') . ')';
                break;
            case 'week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                $periodLabel = 'هذا الأسبوع (' . $startDate->format('Y-m-d') . ' إلى ' . $endDate->format('Y-m-d') . ')';
                break;
            case 'year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                $periodLabel = 'هذه السنة (' . $now->year . ')';
                break;
            case 'custom':
                if ($fromDate && $toDate) {
                    $startDate = Carbon::parse($fromDate)->startOfDay();
                    $endDate = Carbon::parse($toDate)->endOfDay();
                    $periodLabel = 'من ' . $startDate->format('Y-m-d') . ' إلى ' . $endDate->format('Y-m-d');
                } elseif ($fromDate) {
                    $startDate = Carbon::parse($fromDate)->startOfDay();
                    $endDate = $now->copy()->endOfDay();
                    $periodLabel = 'من ' . $startDate->format('Y-m-d') . ' حتى اليوم';
                } else {
                    $period = 'month';
                    $startDate = $now->copy()->startOfMonth();
                    $endDate = $now->copy()->endOfMonth();
                    $periodLabel = 'هذا الشهر (' . $now->format('Y-m') . ')';
                }
                break;
            case 'month':
            default:
                $period = 'month';
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $periodLabel = 'هذا الشهر (' . $now->format('Y-m') . ')';
                break;
        }

        // Get all branch shipping methods
        $branches = ShippingMethod::where('type', 'branch')->get();
        if ($branches->isEmpty()) {
            $branches = ShippingMethod::all();
        }
        $branchIds = $branches->pluck('id')->toArray();

        // Determine target branch IDs for filtering
        $filterBranchIds = $branchIds;
        if ($selectedBranchId && $selectedBranchId !== 'all') {
            $filterBranchIds = [(int)$selectedBranchId];
        }

        // Base Orders Query
        $ordersQuery = Order::query()
            ->where(function ($q) use ($filterBranchIds) {
                $q->whereIn('shipping_method_id', $filterBranchIds)
                  ->orWhereIn('shipping_method', array_map('strval', $filterBranchIds))
                  ->orWhereHas('shipping', function ($sub) use ($filterBranchIds) {
                      $sub->whereIn('id', $filterBranchIds);
                  });
            })
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate])
                  ->orWhereBetween('date', [$startDate->format('Y-m-d 00:00:00'), $endDate->format('Y-m-d 23:59:59')]);
            });

        // Status filter
        if ($selectedStatus && $selectedStatus !== 'all') {
            $ordersQuery->where('status', $selectedStatus);
        } else {
            $ordersQuery->whereNotIn('status', ['canceled', 'cancelled', 'rejected']);
        }

        // Product filter (if selected)
        if ($selectedProductId && $selectedProductId !== 'all') {
            $ordersQuery->whereHas('orderDetails', function ($q) use ($selectedProductId) {
                $q->where('product_id', $selectedProductId);
            });
        }

        // Retrieve orders with relations
        $orders = $ordersQuery->with([
            'orderDetails' => function ($q) use ($selectedProductId) {
                if ($selectedProductId && $selectedProductId !== 'all') {
                    $q->where('product_id', $selectedProductId);
                }
                $q->with(['products.translations', 'products.brands.translations']);
            },
            'shipping'
        ])->get();

        // Initialize branch aggregation dictionary
        $branchStats = [];
        foreach ($branches as $branch) {
            if ($selectedBranchId && $selectedBranchId !== 'all' && $branch->id != $selectedBranchId) {
                continue;
            }
            $branchStats[$branch->id] = [
                'branch_id' => $branch->id,
                'branch_name' => $branch->name,
                'branch_address' => $branch->address ?: 'المكتبة',
                'orders_count' => 0,
                'total_books' => 0,
                'total_revenue' => 0,
                'items' => [], // product_id => item stats
            ];
        }

        $grandTotalBooks = 0;
        $grandTotalRevenue = 0;
        $grandTotalOrders = $orders->count();
        $allProductsSummary = [];

        foreach ($orders as $order) {
            // Identify branch
            $bId = $order->shipping_method_id ?: (is_numeric($order->shipping_method) ? (int)$order->shipping_method : null);
            if (!$bId || !isset($branchStats[$bId])) {
                if ($order->shipping && isset($branchStats[$order->shipping->id])) {
                    $bId = $order->shipping->id;
                } else {
                    $matchedBranch = $branches->first(function ($b) use ($order) {
                        return $b->name == $order->shipping_name || $b->name == $order->address;
                    });
                    if ($matchedBranch && isset($branchStats[$matchedBranch->id])) {
                        $bId = $matchedBranch->id;
                    } else {
                        $bId = 'other';
                        if (!isset($branchStats[$bId])) {
                            $branchStats[$bId] = [
                                'branch_id' => null,
                                'branch_name' => $order->shipping_name ?: ($order->address ?: 'فرع آخر'),
                                'branch_address' => $order->shipping_address ?: '—',
                                'orders_count' => 0,
                                'total_books' => 0,
                                'total_revenue' => 0,
                                'items' => [],
                            ];
                        }
                    }
                }
            }

            $branchStats[$bId]['orders_count']++;

            foreach ($order->orderDetails as $detail) {
                $qty = (int) ($detail->amout ?? 1);
                $unitPrice = (float) ($detail->price ?? 0);
                $itemTotal = (float) ($detail->total_price ?: ($qty * $unitPrice));

                $branchStats[$bId]['total_books'] += $qty;
                $branchStats[$bId]['total_revenue'] += $itemTotal;

                $grandTotalBooks += $qty;
                $grandTotalRevenue += $itemTotal;

                $product = $detail->products;
                $pId = $detail->product_id ?: 0;
                $pName = $product ? ($product->short_name ?: $product->name) : ('كتاب #' . $pId);
                $brandTitle = '—';
                if ($product && $product->brands) {
                    $brandTitle = $product->brands->title ?? $product->brands->name ?? '—';
                }

                // Add to branch items
                if (!isset($branchStats[$bId]['items'][$pId])) {
                    $branchStats[$bId]['items'][$pId] = [
                        'product_id' => $pId,
                        'product_name' => $pName,
                        'author' => $brandTitle,
                        'unit_price' => $unitPrice,
                        'quantity' => 0,
                        'total_amount' => 0,
                    ];
                }
                $branchStats[$bId]['items'][$pId]['quantity'] += $qty;
                $branchStats[$bId]['items'][$pId]['total_amount'] += $itemTotal;

                // Add to overall products summary
                if (!isset($allProductsSummary[$pId])) {
                    $allProductsSummary[$pId] = [
                        'product_id' => $pId,
                        'product_name' => $pName,
                        'brand_name' => $brandTitle,
                        'unit_price' => $unitPrice,
                        'total_quantity' => 0,
                        'total_revenue' => 0,
                        'branch_sales' => [],
                    ];
                }
                $allProductsSummary[$pId]['total_quantity'] += $qty;
                $allProductsSummary[$pId]['total_revenue'] += $itemTotal;
                $currentBranchName = $branchStats[$bId]['branch_name'];
                $allProductsSummary[$pId]['branch_sales'][$currentBranchName] = ($allProductsSummary[$pId]['branch_sales'][$currentBranchName] ?? 0) + $qty;
            }
        }

        // Sort items inside each branch by quantity descending
        foreach ($branchStats as &$b) {
            if (!empty($b['items'])) {
                $itemsArray = array_values($b['items']);
                usort($itemsArray, function ($a, $b) {
                    return $b['quantity'] <=> $a['quantity'];
                });
                $b['items'] = $itemsArray;
            } else {
                $b['items'] = [];
            }
        }
        unset($b);

        // Sort overall products by quantity descending
        $allProductsList = array_values($allProductsSummary);
        usort($allProductsList, function ($a, $b) {
            return $b['total_quantity'] <=> $a['total_quantity'];
        });

        // Top branch
        $topBranchName = '—';
        $topBranchBooks = 0;
        foreach ($branchStats as $b) {
            if ($b['total_books'] > $topBranchBooks) {
                $topBranchBooks = $b['total_books'];
                $topBranchName = $b['branch_name'] . ' (' . $b['total_books'] . ' كتاب)';
            }
        }

        // Top product
        $topProductName = '—';
        if (!empty($allProductsList) && $allProductsList[0]['total_quantity'] > 0) {
            $topProductName = $allProductsList[0]['product_name'] . ' (' . $allProductsList[0]['total_quantity'] . ' نسخة)';
        }

        return [
            'period' => $period,
            'period_label' => $periodLabel,
            'from_date' => $startDate->format('Y-m-d'),
            'to_date' => $endDate->format('Y-m-d'),
            'selected_branch_id' => $selectedBranchId ?: 'all',
            'selected_product_id' => $selectedProductId ?: 'all',
            'selected_status' => $selectedStatus,
            'grand_total_books' => $grandTotalBooks,
            'grand_total_revenue' => $grandTotalRevenue,
            'grand_total_orders' => $grandTotalOrders,
            'top_branch' => $topBranchName,
            'top_product' => $topProductName,
            'branches_stats' => array_values($branchStats),
            'products_summary' => $allProductsList,
            'branches_list' => $branches,
        ];
    }
}
