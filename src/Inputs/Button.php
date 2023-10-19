<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Confirm;
use SlackPhp\BlockKit\Partials\PlainText;

class Button extends InputElement
{
    use HasConfirm;

    private const STYLE_PRIMARY = 'primary';
    private const STYLE_DANGER = 'danger';

    private ?PlainText $text = null;

    private ?string $value = null;

    private ?string $url = null;

    private ?string $style = null;

    public function setText(PlainText $text): static
    {
        $this->text = $text->setParent($this);

        return $this;
    }

    public function text(string $text): static
    {
        return $this->setText(new PlainText($text));
    }

    public function value(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function asPrimary(): static
    {
        $this->style = self::STYLE_PRIMARY;

        return $this;
    }

    public function asDangerous(): static
    {
        $this->style = self::STYLE_DANGER;

        return $this;
    }

    public function validate(): void
    {
        if ($this->text instanceof PlainText) {
            $this->text->validate();
        }

        if ($this->confirm instanceof Confirm) {
            $this->confirm->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->text instanceof PlainText) {
            $data['text'] = $this->text->toArray();
        }

        if ($this->value !== null && $this->value !== '') {
            $data['value'] = $this->value;
        }

        if ($this->url !== null && $this->url !== '') {
            $data['url'] = $this->url;
        }

        if ($this->style !== null && $this->style !== '') {
            $data['style'] = $this->style;
        }

        if ($this->confirm instanceof Confirm) {
            $data['confirm'] = $this->confirm->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('text')) {
            $this->setText(PlainText::fromArray($data->useElement('text')));
        }

        if ($data->has('value')) {
            $this->value($data->useValue('value'));
        }

        if ($data->has('url')) {
            $this->url($data->useValue('url'));
        }

        if ($data->has('style')) {
            switch ($data->useValue('style')) {
                case self::STYLE_PRIMARY:
                    $this->asPrimary();

                    break;

                case self::STYLE_DANGER:
                    $this->asDangerous();

                    break;
            }
        }

        if ($data->has('confirm')) {
            $this->setConfirm(Confirm::fromArray($data->useElement('confirm')));
        }

        parent::hydrate($data);
    }
}
