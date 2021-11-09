<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Partials\Confirm;

trait HasConfirm
{
    /** @var Confirm */
    private $confirm;

    /**
     * @param Confirm $confirm
     */
    public function setConfirm(Confirm $confirm): static
    {
        $this->confirm = $confirm->setParent($this);

        return $this;
    }

    /**
     * @param string $title
     * @param string $text
     * @param string $confirm
     * @param string $deny
     */
    public function confirm(string $title, string $text, string $confirm = 'OK', string $deny = 'Cancel'): static
    {
        return $this->setConfirm(new Confirm($title, $text, $confirm, $deny));
    }
}
