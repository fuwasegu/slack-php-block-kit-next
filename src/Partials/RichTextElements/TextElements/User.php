<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class User extends TextElement
{
    private ?string $userId = null;

    private ?array $style = null;

    /**
     * Set user ID
     */
    public function setUserId(string $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get user ID
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * Set style
     */
    public function setStyle(array $style): static
    {
        // Verify that style attributes are boolean values
        foreach ($style as $key => $value) {
            if (!in_array($key, ['bold', 'italic', 'strike', 'highlight', 'client_highlight', 'unlink'], true)) {
                throw new Exception('Invalid style property for User element: %s', [$key]);
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
        return 'user';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        if ($this->userId === null) {
            throw new Exception('User element must have a user_id value');
        }

        // Validate style if set
        if ($this->style !== null) {
            foreach ($this->style as $key => $value) {
                if (!in_array($key, ['bold', 'italic', 'strike', 'highlight', 'client_highlight', 'unlink'], true)) {
                    throw new Exception('Invalid style property for User element: %s', [$key]);
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
        $data['user_id'] = $this->userId;

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

        if ($data->has('user_id')) {
            $this->setUserId($data->useValue('user_id'));
        }

        if ($data->has('style')) {
            $this->setStyle($data->useArray('style'));
        }
    }
}
