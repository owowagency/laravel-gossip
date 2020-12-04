<?php

namespace OwowAgency\Gossip\Models\Concerns;

trait HasDefaultRelations
{
    /**
     * Scope a query to only include popular users.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $args
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelations($query, ...$args)
    {
        if (! method_exists($this, 'getDefaultRelations')) {
            return $query;
        }

        return $query->with($this->getDefaultRelations(...$args));
    }
}
