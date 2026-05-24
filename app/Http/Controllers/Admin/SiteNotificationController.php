<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteNotification;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SiteNotificationController extends Controller
{
    public function index()
    {
        return view('dashboard.pages.site_notifications.index');
    }

    public function create()
    {
        return view('dashboard.pages.site_notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'is_active' => 'required|boolean'
        ], [
            'content.required' => 'المحتوى مطلوب',
            'is_active.required' => 'الحالة مطلوبة',
        ]);

        SiteNotification::create([
            'content' => $request->content,
            'is_active' => $request->is_active
        ]);

        return redirect()->route('dashboard.site_notifications.index')->with('success', 'تم إضافة التنبيه بنجاح');
    }

    public function edit($id)
    {
        $notification = SiteNotification::findOrFail($id);
        return view('dashboard.pages.site_notifications.edit', compact('notification'));
    }

    public function update(Request $request, $id)
    {
        $notification = SiteNotification::findOrFail($id);

        $request->validate([
            'content' => 'required|string',
            'is_active' => 'required|boolean'
        ], [
            'content.required' => 'المحتوى مطلوب',
            'is_active.required' => 'الحالة مطلوبة',
        ]);

        $notification->update([
            'content' => $request->content,
            'is_active' => $request->is_active
        ]);

        return redirect()->route('dashboard.site_notifications.index')->with('success', 'تم تحديث التنبيه بنجاح');
    }

    public function destroy($id)
    {
        try {
            $notification = SiteNotification::findOrFail($id);
            $notification->delete();
            return response()->json([
                'success' => true,
                'message' => 'تم حذف التنبيه بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف التنبيه'
            ], 500);
        }
    }

    public function datatable(Request $request)
    {
        $query = SiteNotification::query()->orderBy('created_at', 'desc');

        return DataTables::of($query)
            ->addColumn('content', function ($row) {
                return strlen(strip_tags($row->content)) > 100
                    ? mb_substr(strip_tags($row->content), 0, 100) . '...'
                    : strip_tags($row->content);
            })
            ->addColumn(
                'is_active',
                fn($row) => $row->is_active
                ? '<span class="badge bg-success">نشط</span>'
                : '<span class="badge bg-secondary">غير نشط</span>'
            )
            ->addColumn('actions', function ($row) {
                $editBtn = '<a href="' . route('dashboard.site_notifications.edit', $row->id) . '" class="btn btn-sm btn-primary mx-1">
                    <i class="fa fa-edit"></i> تعديل
                </a>';

                $deleteBtn = '<button class="btn btn-sm btn-danger mx-1" onclick="deleteNotification(' . $row->id . ')">
                    <i class="fa fa-trash"></i> حذف
                </button>';

                return '<div class="d-flex gap-1">' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['is_active', 'actions'])
            ->make(true);
    }
}
