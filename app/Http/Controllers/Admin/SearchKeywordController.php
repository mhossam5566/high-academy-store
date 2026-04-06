<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SearchKeyword;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SearchKeywordController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.search_keywords.index');
    }

    public function create()
    {
        return view('dashboard.pages.search_keywords.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'keyword.required' => 'كلمة البحث مطلوبة',
            'keyword.max' => 'كلمة البحث يجب ألا تتجاوز 255 حرف',
            'display_order.integer' => 'الترتيب يجب أن يكون رقماً صحيحاً',
            'status.required' => 'الحالة مطلوبة',
            'status.in' => 'الحالة يجب أن تكون نشط أو غير نشط',
        ]);

        $maxOrder = SearchKeyword::max('display_order') ?? 0;
        $order = $request->filled('display_order') ? (int) $request->display_order : ($maxOrder + 1);

        SearchKeyword::create([
            'keyword' => $request->keyword,
            'display_order' => $order,
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard.search-keywords')->with('success', 'تم إضافة كلمة البحث بنجاح');
    }

    public function edit(SearchKeyword $searchKeyword)
    {
        return view('dashboard.pages.search_keywords.edit', compact('searchKeyword'));
    }

    public function update(Request $request, SearchKeyword $searchKeyword)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'display_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ], [
            'keyword.required' => 'كلمة البحث مطلوبة',
            'keyword.max' => 'كلمة البحث يجب ألا تتجاوز 255 حرف',
            'display_order.integer' => 'الترتيب يجب أن يكون رقماً صحيحاً',
            'status.required' => 'الحالة مطلوبة',
            'status.in' => 'الحالة يجب أن تكون نشط أو غير نشط',
        ]);

        $searchKeyword->update([
            'keyword' => $request->keyword,
            'display_order' => $request->filled('display_order') ? (int) $request->display_order : $searchKeyword->display_order,
            'status' => $request->status,
        ]);

        return redirect()->route('dashboard.search-keywords')->with('success', 'تم تحديث كلمة البحث بنجاح');
    }

    public function destroy(SearchKeyword $searchKeyword)
    {
        try {
            $searchKeyword->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف كلمة البحث بنجاح',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف كلمة البحث',
            ], 500);
        }
    }

    public function datatable(Request $request)
    {
        $query = SearchKeyword::query()->orderBy('display_order');

        return DataTables::of($query)
            ->addColumn('keyword', fn($row) => '<strong>' . e($row->keyword) . '</strong>')
            ->addColumn('display_order', fn($row) => $row->display_order)
            ->addColumn(
                'status',
                fn($row) => $row->status === 'active'
                ? '<span class="badge bg-success">نشط</span>'
                : '<span class="badge bg-secondary">غير نشط</span>'
            )
            ->addColumn('actions', function ($row) {
                $editBtn = '<a href="' . route('dashboard.search-keywords.edit', $row->id) . '" class="btn btn-sm btn-primary mx-1">
                    <i class="fa fa-edit"></i> تعديل
                </a>';
                $deleteBtn = '<button class="btn btn-sm btn-danger mx-1" onclick="deleteKeyword(' . $row->id . ')">
                    <i class="fa fa-trash"></i> حذف
                </button>';
                return '<div class="d-flex gap-1">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['keyword', 'status', 'actions'])
            ->make(true);
    }
}
