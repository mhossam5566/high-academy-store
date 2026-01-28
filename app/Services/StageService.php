<?php

namespace App\Services;

use App\Models\Stage;
use App\Traits\MediaHandler;
use Illuminate\Validation\Rule;


class StageService
{
    use MediaHandler;

    # Index
    public function findAll()
    {
        $Stage = Stage::with('translations')->newQuery();
        return $Stage;
    }

    # Insert
    public function save($request, $data)
    {
        if ($request->photo) {
            $data['photo'] = self::upload($request->file('photo'), 'images/Stages');
        }
        $Stage = Stage::create($data);
        return $Stage;
    }

    # Edit
    public function update($request, $Stage, $data)
    {
        $data = $request->only('title:ar', 'title:en', 'description:ar', 'description:en', 'is_active');

        if ($request->photo) {
            if ($Stage->photo && $Stage->photo != 'default.png') {
                self::deleteMedia($Stage->photo);
            }
            $data['photo'] = self::upload($request->file('photo'), 'images/Stages');
        }

        $Stage->update($data);
        return $Stage;
    }
}
