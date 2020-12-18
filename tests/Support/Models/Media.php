<?php

namespace OwowAgency\Gossip\Tests\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
use OwowAgency\Gossip\Tests\Support\Database\Factories\MediaFactory;

class Media extends BaseMedia
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return MediaFactory::new();
    }
}
