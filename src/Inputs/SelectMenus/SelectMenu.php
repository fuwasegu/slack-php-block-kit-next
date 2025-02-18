<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Inputs\{HasPlaceholder, HasConfirm, InputElement};
use SlackPhp\BlockKit\Partials\{Confirm, PlainText};

abstract class SelectMenu extends InputElement
{
    use HasConfirm;
    use HasPlaceholder;

    public function validate(): void
    {
        if (!$this->placeholder instanceof PlainText) {
            throw new Exception('Select menus must contain a "placeholder"');
        }

        $this->placeholder->validate();

        if ($this->confirm instanceof Confirm) {
            $this->confirm->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $data['placeholder'] = $this->placeholder->toArray();

        if ($this->confirm instanceof Confirm) {
            $data['confirm'] = $this->confirm->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('placeholder')) {
            $this->setPlaceholder(PlainText::fromArray($data->useElement('placeholder')));
        }

        if ($data->has('confirm')) {
            $this->setConfirm(Confirm::fromArray($data->useElement('confirm')));
        }

        parent::hydrate($data);
    }
}
