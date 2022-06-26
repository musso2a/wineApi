<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed provenance
 * @property mixed trade
 * @property mixed color
 * @property mixed images
 * @property mixed description
 * @property mixed condition
 * @property mixed price
 * @property mixed year
 * @property mixed name
 * @property mixed id
 * @property mixed updated_at
 * @property mixed created_at
 * @property mixed user_id
 */
class WineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id ?? '-',
            'name' => $this->name,
            'year' => $this->year,
            'price' => $this->price,
            'condition' => $this->condition,
            'description' => $this->description,
            'images' => $this->when( !empty($this->images) , function () {
                $images = json_decode($this->images) ?? $this->images;
                if (is_iterable($images) ) {
                    return collect($images)->map(function ($image) {
                        return url($image);
                    });
                } else {
                    return url($images);
                }

            }),
            'color' => $this->color,
            'trade' => $this->trade,
            'provenance' => $this->provenance,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
        ];

    }
}
