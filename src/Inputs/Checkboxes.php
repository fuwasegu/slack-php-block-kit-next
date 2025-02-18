<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Partials\{Confirm, HasOptions, OptionsConfig};
use SlackPhp\BlockKit\HydrationData;

class Checkboxes extends InputElement
{
    use HasConfirm;
    use HasOptions;

    protected function getOptionsConfig(): OptionsConfig
    {
        return OptionsConfig::new()
            ->setMinOptions(1)
            ->setMaxOptions(10)
            ->setMaxInitialOptions(10);
    }

    public function validate(): void
    {
        $this->validateOptions();
        $this->validateInitialOptions();

        if ($this->confirm instanceof Confirm) {
            $this->confirm->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray() + $this->getOptionsAsArray() + $this->getInitialOptionsAsArray();

        if ($this->confirm instanceof Confirm) {
            $data['confirm'] = $this->confirm->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        $this->hydrateOptions($data);

        if ($data->has('confirm')) {
            $this->setConfirm(Confirm::fromArray($data->useElement('confirm')));
        }

        parent::hydrate($data);
    }
}
