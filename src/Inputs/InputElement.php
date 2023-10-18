<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Element;
use SlackPhp\BlockKit\HydrationData;

abstract class InputElement extends Element
{
    /**
     * @var string
     */
    private $actionId;

    public function __construct(?string $actionId = null)
    {
        if (!empty($actionId)) {
            $this->actionId($actionId);
        }
    }

    /**
     * @return static
     */
    public function actionId(string $actionId)
    {
        $this->actionId = $actionId;

        return $this;
    }

    public function getActionId(): ?string
    {
        return $this->actionId;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (!empty($this->actionId)) {
            $data['action_id'] = $this->actionId;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('action_id')) {
            $this->actionId($data->useValue('action_id'));
        }

        parent::hydrate($data);
    }
}
