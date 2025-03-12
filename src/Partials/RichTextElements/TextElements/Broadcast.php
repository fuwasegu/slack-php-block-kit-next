<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Broadcast extends TextElement
{
    private ?string $range = null;

    /**
     * Set broadcast range
     */
    public function setRange(string $range): static
    {
        if (!in_array($range, ['here', 'channel', 'everyone'], true)) {
            throw new Exception('Broadcast range must be one of: here, channel, everyone');
        }

        $this->range = $range;

        return $this;
    }

    /**
     * Set range to 'here'
     */
    public function here(): static
    {
        return $this->setRange('here');
    }

    /**
     * Set range to 'channel'
     */
    public function channel(): static
    {
        return $this->setRange('channel');
    }

    /**
     * Set range to 'everyone'
     */
    public function everyone(): static
    {
        return $this->setRange('everyone');
    }

    /**
     * Get element type
     */
    public function getElementType(): string
    {
        return 'broadcast';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        if ($this->range === null) {
            throw new Exception('Broadcast element must have a range value');
        }
    }

    /**
     * Convert the element to an array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['range'] = $this->range;

        return $data;
    }

    /**
     * Generate an element from an array
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('range')) {
            $this->setRange($data->useValue('range'));
        }
    }
}
