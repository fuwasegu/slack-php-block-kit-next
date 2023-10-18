<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;

class ChannelSelectMenu extends SelectMenu
{
    /**
     * @var string
     */
    private $initialChannel;

    /**
     * @var bool
     */
    private $responseUrlEnabled;

    /**
     * @return static
     */
    public function initialChannel(string $initialChannel)
    {
        $this->initialChannel = $initialChannel;

        return $this;
    }

    /**
     * @return static
     */
    public function responseUrlEnabled(bool $enabled)
    {
        $this->responseUrlEnabled = $enabled;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (!empty($this->initialChannel)) {
            $data['initial_channel'] = $this->initialChannel;
        }

        if (!empty($this->responseUrlEnabled)) {
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
