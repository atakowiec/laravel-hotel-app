<?php

namespace App\Traits;

trait WithFlashMessage
{
    public function addFlashMessage($message): void
    {
        $this->emit('flashMessage', $message);
    }

    public abstract function emit($event, ...$params);
}
