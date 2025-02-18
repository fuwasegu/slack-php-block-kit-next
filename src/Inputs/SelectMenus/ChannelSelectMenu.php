<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;

class ChannelSelectMenu extends SelectMenu
{
    private ?string $initialChannel = null;

    private bool $responseUrlEnabled = false;

    public function initialChannel(string $initialChannel): static
    {
        $this->initialChannel = $initialChannel;

        return $this;
    }

    public function responseUrlEnabled(bool $enabled): static
    {
        $this->responseUrlEnabled = $enabled;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (is_string($this->initialChannel) && $this->initialChannel !== '') {
            $data['initial_channel'] = $this->initialChannel;
        }

        if ($this->responseUrlEnabled) {
            $data['response_url_enabled'] = $this->responseUrlEnabled;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_channel')) {
            $this->initialChannel($data->useValue('initial_channel'));
        }

        if ($data->has('response_url_enabled')) {
            $this->responseUrlEnabled($data->useValue('response_url_enabled'));
        }

        parent::hydrate($data);
    }
}
