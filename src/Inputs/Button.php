<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Confirm;
use SlackPhp\BlockKit\Partials\PlainText;

class Button extends InputElement
{
    use HasConfirm;

    private const STYLE_PRIMARY = 'primary';
    private const STYLE_DANGER = 'danger';

    /** @var PlainText */
    private $text;

    /** @var string */
    private $value;

    /** @var string|null */
    private $url;

    /** @var string|null */
    private $style;

    /**
    * @return static
    */
    public function setText(PlainText $text)
    {
        $this->text = $text->setParent($this);

        return $this;
    }

    /**
    * @return static
    */
    public function text(string $text)
    {
        return $this->setText(new PlainText($text));
    }

    /**
    * @return static
    */
    public function value(string $value)
    {
        $this->value = $value;

        return $this;
    }

    /**
    * @return static
    */
    public function url(string $url)
    {
        $this->url = $url;

        return $this;
    }

    /**
    * @return static
    */
    public function asPrimary()
    {
        $this->style = self::STYLE_PRIMARY;

        return $this;
    }

    /**
    * @return static
    */
    public function asDangerous()
    {
        $this->style = self::STYLE_DANGER;

        return $this;
    }

    public function validate(): void
    {
        if (empty($this->text)) {
            throw new Exception('Button must contain "text"');
        }

        $this->text->validate();

        if (!empty($this->confirm)) {
            $this->confirm->validate();
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['text'] = $this->text->toArray();

        if (!empty($this->value)) {
            $data['value'] = $this->value;
        }

        if (!empty($this->url)) {
            $data['url'] = $this->url;
        }

        if (!empty($this->style)) {
            $data['style'] = $this->style;
        }

        if (!empty($this->confirm)) {
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
