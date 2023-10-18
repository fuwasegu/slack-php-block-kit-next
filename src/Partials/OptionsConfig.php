<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

class OptionsConfig
{
    /**
     * @var int|null Minimum number of options supported.
     */
    private $minOptions;

    /**
     * @var int|null Maximum number of options supported.
     */
    private $maxOptions;

    /**
     * @var int|null Maximum number of initial options supported.
     */
    private $maxInitialOptions;

    /**
     * @return static
     */
    public static function new()
    {
        return new static();
    }

    public function __construct()
    {
        $this->minOptions = 1;
    }

    public function getMinOptions(): ?int
    {
        return $this->minOptions;
    }

    /**
     * @return static
     */
    public function setMinOptions(?int $minOptions)
    {
        $this->minOptions = $minOptions;

        return $this;
    }

    public function getMaxOptions(): ?int
    {
        return $this->maxOptions;
    }

    /**
     * @return static
     */
    public function setMaxOptions(?int $maxOptions)
    {
        $this->maxOptions = $maxOptions;

        return $this;
    }

    public function getMaxInitialOptions(): ?int
    {
        return $this->maxInitialOptions;
    }

    /**
     * @return static
     */
    public function setMaxInitialOptions(?int $maxInitialOptions)
    {
        $this->maxInitialOptions = $maxInitialOptions;

        return $this;
    }
}
