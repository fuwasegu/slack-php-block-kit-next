<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit;

/**
 * Stores configuration settings.
 */
final class Config
{
    /** @var bool|null */
    private $defaultVerbatimSetting = null;

    /** @var bool|null */
    private $defaultEmojiSetting = null;

    /**
     * @return static
     */
    public static function new()
    {
        return new static();
    }

    public function getDefaultVerbatimSetting(): ?bool
    {
        return $this->defaultVerbatimSetting;
    }

    /**
     * @return static
     */
    public function setDefaultVerbatimSetting(?bool $verbatim)
    {
        $this->defaultVerbatimSetting = $verbatim;

        return $this;
    }

    public function getDefaultEmojiSetting(): ?bool
    {
        return $this->defaultEmojiSetting;
    }

    /**
     * @return static
     */
    public function setDefaultEmojiSetting(?bool $emoji)
    {
        $this->defaultEmojiSetting = $emoji;

        return $this;
    }
}
