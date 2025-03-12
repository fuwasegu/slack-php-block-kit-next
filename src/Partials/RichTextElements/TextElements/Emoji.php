<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Emoji extends TextElement
{
    private ?string $name = null;

    private ?string $unicode = null;

    /**
     * Set emoji name
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set Unicode value
     */
    public function setUnicode(string $unicode): static
    {
        $this->unicode = $unicode;

        return $this;
    }

    /**
     * Get element type
     */
    public function getElementType(): string
    {
        return 'emoji';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        if ($this->name === null) {
            throw new Exception('Emoji element must have a name value');
        }
    }

    /**
     * Convert the element to an array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['name'] = $this->name;

        if ($this->unicode !== null) {
            $data['unicode'] = $this->unicode;
        }

        return $data;
    }

    /**
     * Generate an element from an array
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('name')) {
            $this->setName($data->useValue('name'));
        }

        if ($data->has('unicode')) {
            $this->setUnicode($data->useValue('unicode'));
        }
    }
}
