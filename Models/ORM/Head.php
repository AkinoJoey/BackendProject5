<?php

namespace Models\ORM;

use Database\DataAccess\ORM;

class Head extends ORM{
    protected static ?array $columnTypes = null;

    public function character() : ?ORM {
        return $this->belongsTo(Character::class);
    }
}