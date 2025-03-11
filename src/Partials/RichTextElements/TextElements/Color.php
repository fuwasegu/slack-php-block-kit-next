<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Color extends TextElement
{
    private ?string $value = null;

    /**
     * Set color value
     */
    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get color value
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Get element type
     */
    public function getElementType(): string
    {
        return 'color';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        if ($this->value === null) {
            throw new Exception('Color element must have a value');
        }
    }

    /**
     * Convert the element to an array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['value'] = $this->value;

        return $data;
    }

    /**
     * Generate an element from an array
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('value')) {
            $this->setValue($data->useValue('value'));
        }
    }
}
