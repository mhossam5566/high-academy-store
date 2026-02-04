<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\OfferService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class OfferController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    public function index()
    {
        return view('dashboard.pages.offers.index');
    }

    public function datatable()
    {
        $offers = $this->offerService->getAllOffers();
        return DataTables::of($offers)
            ->addColumn('image', function ($row) {
                $imageUrl = $row->image_path ?? null;
                if ($imageUrl) {
                    return '<img src="' . $imageUrl . '" alt="offer-image" style="height:120px;width:150px" class="avatar rounded me-2">';
                }
                return '<span class="text-muted">لا توجد صورة</span>';
            })
            ->addColumn('operation', function ($row) {
                $edit = '<a href="' . route('dashboard.offers.edit', $row->id) . '" class="btn btn-sm btn-primary me-1">
                    <i class="ti ti-edit me-1"></i>تعديل
                </a>';
                $delete = '<a offer_id="' . $row->id . '" class="btn btn-sm btn-danger delete_btn">
                    <i class="ti ti-trash me-1"></i>حذف
                </a>';
                return $edit . ' ' . $delete;
            })
            ->rawColumns(['image', 'operation'])
            ->toJson();
    }

    public function create()
    {
        return view('dashboard.pages.offers.create');
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $data = $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // Store offer
            $this->offerService->storeOffer($request);

            return response()->json(['message' => 'تم إضافة العرض بنجاح'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function edit($id)
    {
        return view('dashboard.pages.offers.edit', ['offer' => $this->offerService->getOfferById($id)]);
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $data = $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            // Update offer
            $this->offerService->updateOffer($request, $id);

            return response()->json(['message' => 'تم تحديث العرض بنجاح'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $this->offerService->deleteOffer($id);
            return response()->json(['success' => 'Offer deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
