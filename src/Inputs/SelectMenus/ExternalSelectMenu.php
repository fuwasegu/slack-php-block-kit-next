<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Option;

class ExternalSelectMenu extends SelectMenu
{
    private ?Option $initialOption = null;

    private ?int $minQueryLength = null;

    public function initialOption(string $name, string $value): static
    {
        $this->initialOption = Option::new($name, $value);
        $this->initialOption->setParent($this);

        return $this;
    }

    public function minQueryLength(int $minQueryLength): static
    {
        $this->minQueryLength = $minQueryLength;

        return $this;
    }

    public function validate(): void
    {
        parent::validate();

        if ($this->initialOption instanceof Option) {
            $this->initialOption->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->initialOption instanceof Option) {
            $data['initial_option'] = $this->initialOption->toArray();
        }

        if ($this->minQueryLength !== null) {
            $data['min_query_length'] = $this->minQueryLength;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_option')) {
            $this->initialOption = Option::fromArray($data->useElement('initial_option'));
        }

        if ($data->has('min_query_length')) {
            $this->minQueryLength($data->useValue('min_query_length'));
        }

        parent::hydrate($data);
    }
}
