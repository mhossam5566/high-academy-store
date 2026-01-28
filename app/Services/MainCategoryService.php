<?php

namespace App\Services;

use App\Models\MainCategory;
use App\Traits\MediaHandler;

class MainCategoryService
{
    use MediaHandler;
    public function save(array $data) // Fix: Only accept $data, not $request
    {
        return MainCategory::create($data);
    }

    public function findAll()
    {
        return MainCategory::all();
    }

    public function update($request, $category, $data)
    {


        if ($request->hasFile('icon_image')) {
            if ($category->icon_image && $category->icon_image != 'default.png') {
                self::deleteMedia($category->icon_image);
            }
            $icon_image = self::upload($request->file('icon_image'), 'images/categories');
            // ✅ Update category
            $category->update([
                'name' => $data['name'],
                'icon_image' => $icon_image
            ]);
        } else {
            $category->update([
                'name' => $data['name']
            ]);
        }

        return $category;
    }


    public function delete(MainCategory $category)
    {
        return $category->delete();
    }
}
