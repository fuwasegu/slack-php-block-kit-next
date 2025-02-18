<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;

class MultiUserSelectMenu extends MultiSelectMenu
{
    /**
     * @var string[]
     */
    private array $initialUsers = [];

    /**
     * @param string[] $initialUsers
     */
    public function initialUsers(array $initialUsers): static
    {
        $this->initialUsers = $initialUsers;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->initialUsers !== []) {
            $data['initial_users'] = $this->initialUsers;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_users')) {
            $this->initialUsers($data->useArray('initial_users'));
        }

        parent::hydrate($data);
    }
}
