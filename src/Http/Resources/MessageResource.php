<?php

namespace OwowAgency\Gossip\Http\Resources;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transforms the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'conversation_id' => $this->resource->conversation_id,
            'body' => $this->resource->body,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
            'current_user_read_message' => $this->getReadAtTimestamp(),
            'user' => $this->whenLoaded(
                'user',
                fn() => resource($this->resource->user)
            ),
            'conversation' => $this->whenLoaded(
                'conversation',
                fn() => resource($this->resource->conversation)
            ),
        ];
    }

    /**
     * Get the read at timestamp of this messages from the current authenticated
     * user.
     *
     * @return \Carbon\Carbon|null
     */
    private function getReadAtTimestamp(): ?Carbon
    {
        if (! $this->resource->relationLoaded('users') || Auth::guest()) {
            return null;
        }

        // Find the current authenticated user in the users relation.
        $user = $this->resource->users->first(function ($user) {
            return Auth::user()->is($user);
        });

        return optional($user)->created_at;
    }
}
