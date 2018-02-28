<?php

namespace App\Inspections;

class Spam
{
    /**
     * All registered inspections.
     *
     * @var array
     */
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    /**
     * Detect spam.
     *
     * @param $body
     * @return bool
     * @throws \Exception
     */
    public function detect($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}