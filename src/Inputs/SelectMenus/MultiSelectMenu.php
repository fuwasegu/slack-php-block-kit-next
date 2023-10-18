<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;

abstract class MultiSelectMenu extends SelectMenu
{
    /**
     * @var int|null
     */
    protected $maxSelectedItems;

    /**
     * @return static
     */
    public function maxSelectedItems(int $maxSelectedItems)
    {
        $this->maxSelectedItems = $maxSelectedItems;

        return $this;
    }

    /**
     * @deprecated Inconsistent method name. Use MultiSelectMenu::maxSelectedItems() instead.
     * @return static
     */
    public function setMaxSelectedItems(int $maxSelectedItems)
    {
        $this->maxSelectedItems = $maxSelectedItems;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (!empty($this->maxSelectedItems)) {
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
