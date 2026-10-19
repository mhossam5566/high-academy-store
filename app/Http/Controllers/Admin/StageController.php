<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stage;
use App\Traits\DeleteTrait;
use App\Traits\GeneralTrait;
use App\Traits\MediaHandler;
use Illuminate\Http\Request;
use App\Services\StageService;
use Illuminate\Validation\Rule;
use App\Http\Requests\stageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditstageRequest;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;


class StageController extends Controller
{
    use MediaHandler, DeleteTrait, GeneralTrait;

    protected $stageService;

    public function __construct(StageService $stageService)
    {
        $this->stageService = $stageService;
    }

    public function index()
    {
        // صفحة المراحل التعليمية في لوحة التحكم الجديدة
        return view('dashboard.pages.stage.index');
    }

    public function datatable()
    {
        $stages = $this->stageService->findAll();
        return DataTables::of($stages)
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            // ->addColumn('description', function ($row) {
            //     return $row->description;
            // })
            // ->addColumn('is_active', function ($row) {
            //     return $row->is_active == 1 ? 'Active' : 'InActive';
            // })
            ->addColumn('photo', function ($row) {
                $image = '<img src ="' . url('storage/' . $row->photo) . '" alt="profile-image" style="height:120px;width:150px" class="avatar rounded me-2" >';
                return $image;
            })
            ->addColumn('operation', function ($row) {
                $edit = '<a href="' . route('dashboard.stage.edit', $row->id) . '" class="btn btn-sm btn-primary me-2">
                    <i class="ti ti-edit me-1"></i>تعديل
                </a>';
                $delete = '<button type="button" data-id="' . $row->id . '" stage_id="' . $row->id . '" class="btn btn-sm btn-danger delete_btn">
                    <i class="ti ti-trash me-1"></i>حذف
                </button>';
                return '<div class="d-inline-flex align-items-center">' . $edit . $delete . '</div>';
            })
            ->rawColumns(['operation' => 'operation', 'photo' => 'photo'])
            ->toJson();
    }

    public function create()
    {
        return view('dashboard.pages.stage.create');
    }

    public function store(stageRequest $request)
    {
        $data = $request->only('title:ar', 'title:en', 'description:ar', 'description:en', 'is_active');
        $stage = $this->stageService->save($request, $data);
        return $this->JsonData($stage);
    }

    public function edit($id)
    {
        $stage = stage::FindOrFail($id);
        return view('dashboard.pages.stage.edit', compact('stage'));
    }

    public function update(EditstageRequest $request)
    {
        $stage = stage::FindOrFail($request->stage_id);
        $data = $request->only('title:ar', 'title:en', 'description:ar', 'description:en', 'is_active');
        $stage = $this->stageService->update($request, $stage, $data);
        return $this->JsonData($stage);
    }

    public function destroy(Request $request, $id = null)
    {
        $id = $request->id ?? $id;
        $stage = Stage::findOrFail($id);
        if ($stage->photo && $stage->photo != 'default.png') {
            self::deleteMedia($stage->photo);
        }
        return $this->Delete($id, $stage);
    }

}
