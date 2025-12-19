<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Traits\ImageTrait;
use App\Traits\DeleteTrait;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    use ImageTrait, DeleteTrait, GeneralTrait;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return view('dashboard.pages.Category.index');
    }

    public function datatable()
    {
        $categories = $this->categoryService->findAll();
        return DataTables::of($categories)
            ->addColumn('title', function ($row) {
                return $row->title;
            })
            ->addColumn('is_parent', function ($row) {
                return $row->is_parent == 1 ? 'yes' : 'no';
            })
            ->addColumn('parent_id', function ($row) {
                return $row->parent_id == NULL ? 'Main Category' : $row->parent->title;
            })
            ->addColumn('photo', function ($row) {
                $image = '<img src ="' . $row->image_path . '" alt="profile-image" style="height:120px;width:150px" class="avatar rounded me-2" >';
                return $image;
            })
            ->addColumn('operation', function ($row) {
                $edit = '<a href="' . route('dashboard.category.edit', $row->id) . '" class="btn btn-sm btn-primary me-1">
                    <i class="ti ti-edit me-1"></i>تعديل
                </a>';
                $delete = '<a category_id="' . $row->id . '" class="btn btn-sm btn-danger delete_btn">
                    <i class="ti ti-trash me-1"></i>حذف
                </a>';
                return $edit . $delete;
            })
            ->rawColumns(['operation' => 'operation', 'photo' => 'photo'])
            ->toJson();
    }

    public function create()
    {
        $cats = Category::where('is_parent', 1)->get();
        return view('dashboard.pages.Category.create', compact('cats'));
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->only('title:ar', 'title:en', 'parent_id', 'is_parent');
        $category = $this->categoryService->save($request, $data);
        return $this->JsonData($category);
    }

    public function edit($id, Request $request)
    {
        $category = Category::findOrFail($id);
        $cats = Category::where('is_parent', 1)->get();
        return view('dashboard.pages.Category.edit', compact('category', 'cats'));
    }

    // public function edit($id)
    // {
    //     $category = Category::FindOrFail($id);
    //     $cats = Category::where('is_parent', 1)->get();
    //     return view('admin.Category.edit', compact('category', 'cats'));
    // }

    public function update(EditCategoryRequest $request)
    {
        $category = Category::FindOrFail($request->category_id);
        $data = $request->only('title:ar', 'title:en', 'parent_id', 'is_parent');
        $category = $this->categoryService->update($request, $category, $data);
        return $this->JsonData($category);
    }

    public function destroy(Request $request)
    {
        $category = Category::FindOrFail($request->id);
        if ($category->photo != 'default.png') {
            Storage::delete('public/images/categories/' . $category->photo);
        }
        return $this->Delete($request->id, $category);
    }

    public function getChildByParentID(Request $request, $id)
    {
        // dd($id);
        $category = Category::find($request->id);
        if ($category) {
            $child_id = Category::getChildByParentID($request->id);
            if (count($child_id) <= 0) {
                return response()->json([
                    'status' => false,
                    'data' => null,
                    'msg' => ''
                ]);
            }
            return response()->json([
                'status' => true,
                'data' => $child_id,
                'msg' => ''
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => null,
                'msg' => 'category not found'
            ]);
        }
    }
}
