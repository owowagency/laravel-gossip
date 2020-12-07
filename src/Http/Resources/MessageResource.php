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
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'body' => $this->body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'current_user_read_message' => $this->getReadAtTimestamp(),
            'user' => $this->whenLoaded(
                'user',
                fn() => resource($this->user)
            ),
            'conversation' => $this->whenLoaded(
                'conversation',
                fn() => resource($this->conversation)
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
        if (! $this->relationLoaded('users') || Auth::guest()) {
            return null;
        }

        // Find the current authenticated user in the users relation.
        $user = $this->users->first(function ($user) {
            return Auth::user()->is($user);
        });

        if ($user === null) {
            return null;
        }

        return $user->pivot->created_at;
    }
}
