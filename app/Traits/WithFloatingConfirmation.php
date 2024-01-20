<?php

namespace App\Traits;

trait WithFloatingConfirmation
{
    use WithFloatingComponent;

    public function confirm($id): void
    {
        $this->onConfirm($id, ...($this->params[$id] ?? []));
        $this->hideFloatingComponent($id);
    }

    abstract public function onConfirm($id, ...$params): void;

    public function onCancel($id, ...$params): void
    {

    }

    public function cancel($id): void
    {
        $this->onCancel($id, ...($this->params[$id] ?? []));
        $this->hideFloatingComponent($id);
    }
}
