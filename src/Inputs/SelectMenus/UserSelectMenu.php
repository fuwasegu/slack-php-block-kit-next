<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;

class UserSelectMenu extends SelectMenu
{
    private ?string $initialUser = null;

    public function initialUser(string $initialUser): static
    {
        $this->initialUser = $initialUser;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (!empty($this->initialUser)) {
            $data['initial_user'] = $this->initialUser;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_user')) {
            $this->initialUser($data->useValue('initial_user'));
        }

        parent::hydrate($data);
    }
}
