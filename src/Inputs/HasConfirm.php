<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Partials\Confirm;

trait HasConfirm
{
    /**
     * @var Confirm
     */
    private $confirm;

    /**
     * @return static
     */
    public function setConfirm(Confirm $confirm)
    {
        $this->confirm = $confirm->setParent($this);

        return $this;
    }

    /**
     * @return static
     */
    public function confirm(string $title, string $text, string $confirm = 'OK', string $deny = 'Cancel')
    {
        return $this->setConfirm(new Confirm($title, $text, $confirm, $deny));
    }
}
