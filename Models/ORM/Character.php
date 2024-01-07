<?php

namespace Models\ORM;

use Database\DataAccess\ORM;

class Character extends ORM
{
    protected static ?array $columnTypes = null;
    
    public function profile(): string
    {
        return sprintf(
            "Name: %s\nDescription: %s\nGender: %s\nSubclass: %s\nRace: %s",
            $this->name,
            $this->description,
            $this->gender,
            $this->subclass,
            $this->race
        );
    }

    public function head() : ?ORM {
        return $this->hasOne(Head::class);
    }
}


