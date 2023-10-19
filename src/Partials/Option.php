<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\{Element, Exception, HydrationData, Type};

/**
 * @see https://api.slack.com/reference/block-kit/composition-objects#option
 */
class Option extends Element
{
    private ?PlainText $text = null;

    private ?string $value = null;

    /**
     * Description text for option. NOTE: Radio Button and Checkbox groups only.
     */
    private ?PlainText $description = null;

    /**
     * URL to load in browser when option is clicked. NOTE: Overflow Menu only.
     */
    private ?string $url = null;

    public static function new(?string $text = null, ?string $value = null): static
    {
        $option = new static();

        if ($text !== null) {
            $option->text($text);
        }

        if ($value !== null) {
            $option->value($value);
        }

        return $option;
    }

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

    public function setDescription(PlainText $description): static
    {
        $this->description = $description->setParent($this);

        return $this;
    }

    public function description(string $description): static
    {
        return $this->setDescription(new PlainText($description));
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function validate(): void
    {
        if ($this->text === null) {
            throw new Exception('Option element must contain a "text" element');
        }

        $this->text->validateWithLength(75);

        if ($this->value === null) {
            throw new Exception('Option element must have a "value" value');
        }

        Text::validateString($this->value, 75);

        $parent = $this->getParent();

        if ($this->description instanceof PlainText) {
            $this->description->validateWithLength(75);
            if ($parent && !in_array($parent->getType(), [Type::CHECKBOXES, Type::RADIO_BUTTONS], true)) {
                throw new Exception('Option "description" can only be applied to checkbox and radio button groups.');
            }
        }

        if ($this->url !== null && $this->url !== '') {
            Text::validateString($this->url, 3000);
            if ($parent && $parent->getType() !== Type::OVERFLOW_MENU) {
                throw new Exception('Option "url" can only be applied to overflow menus.');
            }
        }
    }

    public function toArray(): array
    {
        assert($this->text !== null);

        $data = [
            'text' => $this->text->toArray(),
            'value' => $this->value,
        ];

        if ($this->description instanceof PlainText) {
            $data['description'] = $this->description->toArray();
        }

        if ($this->url !== null && $this->url !== '') {
            $data['url'] = $this->url;
        }

        return parent::toArray() + $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('text')) {
            $this->setText(PlainText::fromArray($data->useElement('text')));
        }

        if ($data->has('value')) {
            $this->value($data->useValue('value'));
        }

        if ($data->has('description')) {
            $this->setDescription(PlainText::fromArray($data->useElement('description')));
        }

        if ($data->has('url')) {
            $this->value($data->useValue('url'));
        }

        parent::hydrate($data);
    }
}
