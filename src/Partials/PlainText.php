<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Kit;

class PlainText extends Text
{
    private bool $emoji = false;

    public function __construct(?string $text = null, ?bool $emoji = null)
    {
        if ($text !== null) {
            $this->text($text);
        }

        $emoji ??= Kit::config()->getDefaultEmojiSetting();
        $this->emoji($emoji);
    }

    public function emoji(?bool $emoji): static
    {
        $this->emoji = $emoji ?? Kit::config()->getDefaultEmojiSetting();

        return $this;
    }

    public function toArray(): array
    {
        return !$this->emoji
            ? parent::toArray() + ['emoji' => false]
            : parent::toArray();
    }

    protected function hydrate(HydrationData $data): void
    {
        $this->emoji($data->useValue('emoji'));

        parent::hydrate($data);
    }
}
