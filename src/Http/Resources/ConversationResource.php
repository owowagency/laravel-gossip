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
        $messageResource = config('gossip.resources.message');

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'users' => $this->whenLoaded(
                'users',
                fn() => resource($this->resource->users)
            ),
            'messages' => $this->whenLoaded(
                'messages',
                fn() => $messageResource::collection($this->resource->messages)
            ),
        ];
    }
}
