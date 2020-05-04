<?php

namespace App\Inspections;

use App\Inspections\Inspection;

class KeyHeldDown implements Inspection
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply cotains spam');
        }
    }
}