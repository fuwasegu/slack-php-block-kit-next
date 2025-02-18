<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Partials\Confirm;

trait HasConfirm
{
    private ?Confirm $confirm = null;

    public function setConfirm(Confirm $confirm): static
    {
        $this->confirm = $confirm->setParent($this);

        return $this;
    }

    public function confirm(string $title, string $text, string $confirm = 'OK', string $deny = 'Cancel'): static
    {
        return $this->setConfirm(new Confirm($title, $text, $confirm, $deny));
    }
}
