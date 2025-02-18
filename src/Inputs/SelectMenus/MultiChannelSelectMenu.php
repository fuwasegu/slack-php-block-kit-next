<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;

class MultiChannelSelectMenu extends MultiSelectMenu
{
    /**
     * @var string[]
     */
    private array $initialChannels = [];

    /**
     * @param string[] $initialChannels
     */
    public function initialChannels(array $initialChannels): static
    {
        $this->initialChannels = $initialChannels;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->initialChannels !== []) {
            $data['initial_channels'] = $this->initialChannels;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_channels')) {
            $this->initialChannels($data->useArray('initial_channels'));
        }

        parent::hydrate($data);
    }
}
