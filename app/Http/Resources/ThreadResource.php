<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ThreadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'image' => $this->image,
            'created_at' => $this->created_at->diffForHumans(),
            'user' => $this->user,
            'likes' => $this->likes->count(),
            'comments' => CommentResource::collection($this->comments),
        ];
    }
}
