<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Text extends TextElement
{
    private ?string $text = null;

    private ?array $style = null;

    /**
     * Set text
     */
    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * Set style
     */
    public function setStyle(array $style): static
    {
        // Verify that style attributes are boolean values
        foreach ($style as $key => $value) {
            if (!in_array($key, ['bold', 'italic', 'strike', 'code'], true)) {
                throw new Exception('Invalid style property for Text element: %s', [$key]);
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
     * Set code style
     */
    public function code(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['code'] = $flag;

        return $this;
    }

    /**
     * Get element type
     */
    public function getElementType(): string
    {
        return 'text';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        if ($this->text === null || $this->text === '') {
            throw new Exception('Text element must have a text value');
        }

        // Validate style if set
        if ($this->style !== null) {
            foreach ($this->style as $key => $value) {
                if (!in_array($key, ['bold', 'italic', 'strike', 'code'], true)) {
                    throw new Exception('Invalid style property for Text element: %s', [$key]);
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
        $data['text'] = $this->text;

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

        if ($data->has('text')) {
            $this->text($data->useValue('text'));
        }

        if ($data->has('style')) {
            $this->setStyle($data->useArray('style'));
        }
    }
}
