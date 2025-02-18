<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Partials\{Confirm, HasOptions, Option, OptionsConfig};
use SlackPhp\BlockKit\HydrationData;

class OverflowMenu extends InputElement
{
    use HasConfirm;
    use HasOptions;

    protected function getOptionsConfig(): OptionsConfig
    {
        return OptionsConfig::new()
            ->setMinOptions(2)
            ->setMaxOptions(5)
            ->setMaxInitialOptions(0);
    }

    public function urlOption(string $text, string $value, string $url): static
    {
        return $this->addOption(Option::new($text, $value)->url($url));
    }

    public function validate(): void
    {
        $this->validateOptions();

        $this->confirm->validate();
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $data += $this->getOptionsAsArray();

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
