<?php

namespace App\Traits;

use Illuminate\Support\MessageBag;

trait hasInputErrorClass
{
    public function getErrorClass($field): string
    {
        return $this->getErrorBag()->has($field) ? "class=is-invalid" : '';
    }

    abstract public function getErrorBag();
}
