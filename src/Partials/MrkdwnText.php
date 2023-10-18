<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Kit;

class MrkdwnText extends Text
{
    private ?bool $verbatim = null;

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
        $this->verbatim = $verbatim;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->verbatim !== null) {
            $data['verbatim'] = $this->verbatim;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        $this->verbatim($data->useValue('verbatim'));

        parent::hydrate($data);
    }
}
