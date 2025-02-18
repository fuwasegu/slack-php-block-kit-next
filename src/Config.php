<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit;

/**
 * Stores configuration settings.
 */
final class Config
{
    private bool $defaultVerbatimSetting = false;

    private bool $defaultEmojiSetting = true;

    public static function new(): self
    {
        return new self();
    }

    public function getDefaultVerbatimSetting(): bool
    {
        return $this->defaultVerbatimSetting;
    }

    public function setDefaultVerbatimSetting(?bool $verbatim): self
    {
        $this->defaultVerbatimSetting = $verbatim;

        return $this;
    }

    public function getDefaultEmojiSetting(): bool
    {
        return $this->defaultEmojiSetting;
    }

    public function setDefaultEmojiSetting(?bool $emoji): self
    {
        $this->defaultEmojiSetting = $emoji;

        return $this;
    }
}
