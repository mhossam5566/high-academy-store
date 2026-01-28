<?php

namespace App\Services;

use App\Models\Product;
use App\Traits\ImageTrait;
use App\Traits\MediaHandler;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class ProductService
{
    use ImageTrait, MediaHandler;

    # Index
    public function findAll()
    {
        $products = Product::with('translations', 'sub_cat_info', 'category', 'brands')->orderby("id", "DESC")->newQuery();
        return $products;
    }

    # Insert
    public function save($request, $data)
    {
        if ($request->hasFile('photo')) {
            $data['photo'] = self::upload($request->file('photo'), 'images/products');
        }
        $product = Product::create($data);
        return $product;
    }

    # Edit
    public function update($request, $product, $data)
    {
        if ($request->hasFile('photo')) {
            if ($product->photo && $product->photo != 'default.png') {
                self::deleteMedia($product->photo);
            }
            $data['photo'] = self::upload($request->file('photo'), 'images/products');
        }
        $product->update($data);
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_deleted' => true]);
        return true;
    }
}
