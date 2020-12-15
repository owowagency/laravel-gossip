<?php

namespace OwowAgency\Gossip\Models\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait HasDateScopes
{
    /**
     * Scope a query to only include models that have been created after the
     * given date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedAfter(Builder $query, $date): Builder
    {
        return $query->where('created_at', '>', Carbon::parse($date));
    }

    /**
     * Scope a query to only include models that have been created after the
     * given date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBefore(Builder $query, $date): Builder
    {
        return $query->where('created_at', '<', Carbon::parse($date));
    }

    /**
     * Scope a query to only include models that have been updated after the
     * given date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedAfter(Builder $query, $date): Builder
    {
        return $query->where('updated_at', '>', Carbon::parse($date));
    }

    /**
     * Scope a query to only include models that have been updated after the
     * given date.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpdatedBefore(Builder $query, $date): Builder
    {
        return $query->where('updated_at', '<', Carbon::parse($date));
    }
}
