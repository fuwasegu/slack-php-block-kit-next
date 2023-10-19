<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Kit;

class MrkdwnText extends Text
{
    private bool $verbatim = false;

    public function __construct(?string $text = null, ?bool $verbatim = null)
    {
        if ($text !== null) {
            $this->text($text);
        }

        $verbatim ??= Kit::config()->getDefaultVerbatimSetting();
        $this->verbatim($verbatim);
    }

    public function verbatim(?bool $verbatim): static
    {
        $this->verbatim = $verbatim ?? Kit::config()->getDefaultVerbatimSetting();

        return $this;
    }

    public function toArray(): array
    {
        return $this->verbatim
            ? parent::toArray() + ['verbatim' => true]
            : parent::toArray();
    }

    protected function hydrate(HydrationData $data): void
    {
        $this->verbatim($data->useValue('verbatim'));

        parent::hydrate($data);
    }
}
