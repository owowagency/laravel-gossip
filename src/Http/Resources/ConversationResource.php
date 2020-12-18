<?php

namespace OwowAgency\Gossip\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transforms the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $resources = config('gossip.resources');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'users' => $this->whenLoaded(
                'users',
                fn() => $resources['user']::collection($this->users),
            ),
            'messages' => $this->whenLoaded(
                'messages',
                fn() => $resources['message']::collection($this->messages)
            ),
        ];
    }
}
