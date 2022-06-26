<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed email
 * @property mixed avatar
 * @property mixed is_major
 * @property mixed note
 * @property mixed subscription
 * @property mixed favorite_wine_id
 * @property mixed created_at
 * @property mixed updated_at
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $wines= $this->whenLoaded('wines');
        $favorite = $this->whenLoaded('favoriteWine');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'avatar' => url($this->avatar),
            'is_major' => $this->is_major,
            'note' => $this->note,
            'subscription' => $this->subscription,
            'favorite_wine_id' => $this->favorite_wine_id,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'wines' => WineResource::collection($wines),
            'favorite_wine' => new WineResource($favorite),
        ];
    }
}
