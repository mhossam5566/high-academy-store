<?php

namespace App\Services;

use App\Models\Brand;
use App\Traits\MediaHandler;
use Illuminate\Validation\Rule;


class BrandService
{
    use MediaHandler;

    # Index
    public function findAll()
    {
        $brands = Brand::with('translations')->newQuery();
        return $brands;
    }

    # Insert
    public function save($request, $data)
    {
        if ($request->hasFile('photo')) {
            $data['photo'] = self::upload($request->file('photo'), 'images/brands');
        }
        $brand = Brand::create($data);
        return $brand;
    }

    # Edit
    public function update($request, $brand, $data)
    {
        if ($request->hasFile('photo')) {
            if ($brand->photo && $brand->photo != 'default.png') {
                self::deleteMedia($brand->photo);
            }
            $data['photo'] = self::upload($request->file('photo'), 'images/brands');
        }

        $brand->update($data);
        return $brand;
    }
}
