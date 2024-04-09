<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::find($this->user_id);
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'body'              => $this->body,
            'user_id'           => new UserResource($user),

        ];
    }
}
