<?php

namespace OwowAgency\Gossip\Support\Exceptions;

use Exception;

class RelationNotLoadedException extends Exception
{
    /**
     * Construct the exception.
     *
     * @param  string  $relation
     */
    public function __construct(string $relation)
    {
        parent::__construct(trans(
            'gossip::general.exceptions.relation_not_loaded',
            compact('relation'),
        ));
    }
}
