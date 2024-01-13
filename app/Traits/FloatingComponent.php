<?php

namespace App\Traits;

trait FloatingComponent
{
    public array $visible = [];

    public function isVisible($id) : bool
    {
        return in_array($id, $this->visible);
    }

    public function showFloatingComponent($id) : void
    {
        $this->visible[] = $id;
    }

    public function hideFloatingComponent($id) : void
    {
        $this->visible = array_filter($this->visible, fn($item) => $item !== $id);
    }
}
