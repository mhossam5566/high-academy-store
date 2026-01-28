<?php

namespace App\Services;

use App\Models\Slider;
use App\Traits\MediaHandler;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class SliderService
{
    use MediaHandler;

    # Index
    public function findAll()
    {
        $slider = Slider::with('translations')->newQuery();
        return $slider;
    }

    # Insert
    public function save($request, $data)
    {
        if ($request->photo) {
            $data['photo'] = self::upload($request->file('photo'), 'images/sliders');
        }
        $slider = Slider::create($data);
        return $slider;
    }

    # Edit
    public function update($request, $slider, $data)
    {
        $data = $request->only('title:ar', 'stage_id', 'title:en', 'description:ar', 'description:en', 'is_active');

        if ($request->photo) {
            if ($slider->photo && $slider->photo != 'default.png') {
                self::deleteMedia($slider->photo);
            }
            $data['photo'] = self::upload($request->file('photo'), 'images/sliders');
        }

        $slider->update($data);
        return $slider;
    }
}
