<?php

namespace App\Services;

use App\Models\Category;
use App\Traits\MediaHandler;
use Illuminate\Validation\Rule;


class CategoryService
{
    use MediaHandler;

    # Index
    public function findAll()
    {
        $categories = Category::with('translations', 'parent')->newQuery();
        return $categories;
    }

    # Insert
    public function save($request, $data)
    {
        if ($request->photo) {
            $data['photo'] = self::upload($request->file('photo'), 'images/categories');
        }
        $data['is_parent'] = $request->input('is_parent', 0);
        $category = Category::create($data);
        return $category;
    }

    # Edit
    public function update($request, $category, $data)
    {
        $data['is_parent'] = $request->input('is_parent', 0);
        if ($request->is_parent == 1) {
            $data['parent_id'] = null;
        }
        if ($request->photo) {
            if ($category->photo && $category->photo != 'default.png') {
                self::deleteMedia($category->photo);
            }
            $data['photo'] = self::upload($request->file('photo'), 'images/categories');
        }

        $category->update($data);
        return $category;
    }
}
