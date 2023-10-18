<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Option;

class MultiExternalSelectMenu extends MultiSelectMenu
{
    /**
     * @var Option[]
     */
    private ?array $initialOptions = null;

    private ?int $minQueryLength = null;

    public function initialOptions(array $options): static
    {
        foreach ($options as $name => $value) {
            $option = Option::new((string)$name, (string)$value);
            $option->setParent($this);
            $this->initialOptions[] = $option;
        }

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

        foreach ($this->initialOptions as $option) {
            $option->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->initialOptions !== null && $this->initialOptions !== []) {
            $data['initial_options'] = array_map(static fn (Option $option): array => $option->toArray(), $this->initialOptions);
        }

        if ($this->minQueryLength !== null) {
            $data['min_query_length'] = $this->minQueryLength;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_options')) {
            $this->initialOptions = [];
            foreach ($data->useElements('initial_options') as $option) {
                $this->initialOptions[] = Option::fromArray($option);
            }
        }

        if ($data->has('min_query_length')) {
            $this->minQueryLength($data->useValue('min_query_length'));
        }

        parent::hydrate($data);
    }
}
