<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Kit;

class MrkdwnText extends Text
{
    /**
     * @var bool
     */
    private $verbatim;

    public function __construct(?string $text = null, ?bool $verbatim = null)
    {
        if ($text !== null) {
            $this->text($text);
        }

        $verbatim ??= Kit::config()->getDefaultVerbatimSetting();
        $this->verbatim($verbatim);
    }

    /**
     * @return static
     */
    public function verbatim(?bool $verbatim)
    {
        $this->verbatim = $verbatim;

        return $this;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (isset($this->verbatim)) {
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
