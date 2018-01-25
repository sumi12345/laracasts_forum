<?php

namespace App;


class Spam
{
    protected $inspections = [
        \App\Inspections\InvalidKeyword::class,
        \App\Inspections\KeyHeldDown::class,
    ];

    public function detect($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}