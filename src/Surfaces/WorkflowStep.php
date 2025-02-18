<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Surfaces;

use SlackPhp\BlockKit\Blocks\Input;
use SlackPhp\BlockKit\HydrationData;

/**
 * A Workflow Step surface are a special case of a Modal, with limited properties, and are used to configure an app's
 * custom workflow step.
 *
 * @see https://api.slack.com/workflows/steps#handle_config_view
 */
class WorkflowStep extends Surface
{
    private ?string $privateMetadata = null;

    private ?string $callbackId = null;

    public function callbackId(string $callbackId): static
    {
        $this->callbackId = $callbackId;

        return $this;
    }

    public function privateMetadata(string $privateMetadata): static
    {
        $this->privateMetadata = $privateMetadata;

        return $this;
    }

    public function newInput(?string $blockId = null): Input
    {
        $block = new Input($blockId);
        $this->add($block);

        return $block;
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->callbackId !== null && $this->callbackId !== '') {
            $data['callback_id'] = $this->callbackId;
        }

        if ($this->privateMetadata !== null && $this->privateMetadata !== '') {
            $data['private_metadata'] = $this->privateMetadata;
        }

        return $data + parent::toArray();
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('callback_id')) {
            $this->callbackId($data->useValue('callback_id'));
        }

        if ($data->has('private_metadata')) {
            $this->privateMetadata($data->useValue('private_metadata'));
        }

        parent::hydrate($data);
    }
}
