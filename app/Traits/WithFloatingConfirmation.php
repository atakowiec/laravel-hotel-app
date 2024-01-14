<?php

namespace App\Traits;

trait WithFloatingConfirmation
{
    use WithFloatingComponent {
        hideFloatingComponent as protected _traitHideFloatingComponent;
        showFloatingComponent as protected _traitShowFloatingComponent;
    }

    public array $params = [];

    public function showFloatingComponent($id, ...$params): void
    {
        $this->params[$id] = $params;
        $this->_traitShowFloatingComponent($id);
    }

    public function confirm($id): void
    {
        $this->onConfirm($id, ...($this->params[$id] ?? []));
        $this->hideFloatingComponent($id);
    }

    abstract public function onConfirm($id, ...$params): void;

    public function onCancel($id, ...$params): void
    {

    }

    public function hideFloatingComponent($id): void
    {
        $this->_traitHideFloatingComponent($id);
        unset($this->params[$id]);
    }

    public function cancel($id): void
    {
        $this->onCancel($id, ...($this->params[$id] ?? []));
        $this->hideFloatingComponent($id);
    }

    public function getParams($id)
    {
        return $this->params[$id] ?? [];
    }
}
