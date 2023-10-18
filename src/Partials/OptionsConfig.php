<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

class OptionsConfig
{
    /**
     * @var int|null minimum number of options supported
     */
    private ?int $minOptions = 1;

    /**
     * @var int|null maximum number of options supported
     */
    private ?int $maxOptions = null;

    /**
     * @var int|null maximum number of initial options supported
     */
    private ?int $maxInitialOptions = null;

    public static function new(): static
    {
        return new static();
    }

    public function getMinOptions(): ?int
    {
        return $this->minOptions;
    }

    public function setMinOptions(?int $minOptions): static
    {
        $this->minOptions = $minOptions;

        return $this;
    }

    public function getMaxOptions(): ?int
    {
        return $this->maxOptions;
    }

    public function setMaxOptions(?int $maxOptions): static
    {
        $this->maxOptions = $maxOptions;

        return $this;
    }

    public function getMaxInitialOptions(): ?int
    {
        return $this->maxInitialOptions;
    }

    public function setMaxInitialOptions(?int $maxInitialOptions): static
    {
        $this->maxInitialOptions = $maxInitialOptions;

        return $this;
    }
}
