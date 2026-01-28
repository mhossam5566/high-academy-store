<?php

namespace App\Services;

use App\Models\Offer;
use App\Traits\MediaHandler;
class OfferService
{
    use MediaHandler;
    public function getAllOffers()
    {
        return Offer::all();
    }

    public function getOfferById($id)
    {
        return Offer::findOrFail($id);
    }

    public function storeOffer($request)
    {
        $data = [];
        if ($request->hasFile('image')) {
            $data['image'] = self::upload($request->file('image'), 'images/offers');
        }
        return Offer::create($data);
    }

    public function updateOffer($request, $id)
    {
        $offer = Offer::findOrFail($id);
        $data = [];
        if ($request->hasFile('image')) {
            if ($offer->image) {
                self::deleteMedia($offer->image);
            }
            $data['image'] = self::upload($request->file('image'), 'images/offers');
        }
        $offer->update($data);
        return $offer;
    }

    public function deleteOffer($id)
    {
        $offer = Offer::findOrFail($id);
        if ($offer->image) {
            self::deleteMedia($offer->image);
        }
        return $offer->delete();
    }
}
