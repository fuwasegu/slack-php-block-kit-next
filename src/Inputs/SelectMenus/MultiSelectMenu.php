<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;

abstract class MultiSelectMenu extends SelectMenu
{
    protected ?int $maxSelectedItems = null;

    public function maxSelectedItems(int $maxSelectedItems): static
    {
        $this->maxSelectedItems = $maxSelectedItems;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->maxSelectedItems !== null && $this->maxSelectedItems !== 0) {
            $data['max_selected_items'] = $this->maxSelectedItems;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('max_selected_items')) {
            $this->maxSelectedItems($data->useValue('max_selected_items'));
        }

        parent::hydrate($data);
    }
}
