<?php

namespace App\Traits;

trait WithFloatingComponent
{
    public array $visible = [];

    public array $params = [];

    public function isVisible($id): bool
    {
        return in_array($id, $this->visible);
    }

    public function showFloatingComponent($id, ...$params): void
    {
        $this->params[$id] = $params;
        $this->visible[] = $id;
    }

    public function hideFloatingComponent($id): void
    {
        $this->visible = array_filter($this->visible, fn($item) => $item !== $id);
        unset($this->params[$id]);
    }

    public function getParams($id)
    {
        return $this->params[$id] ?? [];
    }

    public function getParam($id, $index)
    {
        return $this->params[$id][$index] ?? null;
    }
}
