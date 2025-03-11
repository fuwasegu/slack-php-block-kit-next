<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Channel extends TextElement
{
    private ?string $channelId = null;

    private ?array $style = null;

    /**
     * Set channel ID
     */
    public function setChannelId(string $channelId): static
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * Get channel ID
     */
    public function getChannelId(): ?string
    {
        return $this->channelId;
    }

    /**
     * Set style
     */
    public function setStyle(array $style): static
    {
        // Verify that style attributes are boolean values
        foreach ($style as $key => $value) {
            if (!in_array($key, ['bold', 'italic', 'strike', 'highlight', 'client_highlight', 'unlink'], true)) {
                throw new Exception('Invalid style property for Channel element: %s', [$key]);
            }

            if (!is_bool($value)) {
                throw new Exception('Style property must be a boolean value: %s', [$key]);
            }
        }

        $this->style = $style;

        return $this;
    }

    /**
     * Set bold style
     */
    public function bold(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['bold'] = $flag;

        return $this;
    }

    /**
     * Set italic style
     */
    public function italic(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['italic'] = $flag;

        return $this;
    }

    /**
     * Set strikethrough style
     */
    public function strike(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['strike'] = $flag;

        return $this;
    }

    /**
     * Set highlight style
     */
    public function highlight(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['highlight'] = $flag;

        return $this;
    }

    /**
     * Set client highlight style
     */
    public function clientHighlight(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['client_highlight'] = $flag;

        return $this;
    }

    /**
     * Set unlink style
     */
    public function unlink(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['unlink'] = $flag;

        return $this;
    }

    /**
     * Get element type
     */
    public function getElementType(): string
    {
        return 'channel';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        if ($this->channelId === null) {
            throw new Exception('Channel element must have a channel_id value');
        }

        // Validate style if set
        if ($this->style !== null) {
            foreach ($this->style as $key => $value) {
                if (!in_array($key, ['bold', 'italic', 'strike', 'highlight', 'client_highlight', 'unlink'], true)) {
                    throw new Exception('Invalid style property for Channel element: %s', [$key]);
                }

                if (!is_bool($value)) {
                    throw new Exception('Style property must be a boolean value: %s', [$key]);
                }
            }
        }
    }

    /**
     * Convert the element to an array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['channel_id'] = $this->channelId;

        if ($this->style !== null && $this->style !== []) {
            $data['style'] = $this->style;
        }

        return $data;
    }

    /**
     * Generate an element from an array
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('channel_id')) {
            $this->setChannelId($data->useValue('channel_id'));
        }

        if ($data->has('style')) {
            $this->setStyle($data->useArray('style'));
        }
    }
}
