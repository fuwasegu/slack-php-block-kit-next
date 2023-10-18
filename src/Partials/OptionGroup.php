<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

class OptionGroup extends Element
{
    use HasOptions;

    /**
     * @var PlainText
     */
    private $label;

    /**
     * @param array<string, string>|string[]|null $options
     */
    public static function new(?string $label = null, ?array $options = null): static
    {
        $optionGroup = new static();

        if ($label !== null) {
            $optionGroup->label($label);
        }

        if ($options !== null) {
            $optionGroup->options($options);
        }

        return $optionGroup;
    }

    protected function getOptionsConfig(): OptionsConfig
    {
        return OptionsConfig::new()->setMinOptions(1)->setMaxOptions(100);
    }

    public function setLabel(PlainText $label): static
    {
        $this->label = $label->setParent($this);

        return $this;
    }

    public function label(string $label): static
    {
        return $this->setLabel(new PlainText($label, false));
    }

    public function validate(): void
    {
        if (empty($this->label)) {
            throw new Exception('OptionGroup element must contain a "label" element');
        }

        $this->label->validate();
        $this->validateOptions();
    }

    public function toArray(): array
    {
        return parent::toArray()
            + ['label' => $this->label->toArray()]
            + $this->getOptionsAsArray();
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('label')) {
            $this->setLabel(PlainText::fromArray($data->useElement('label')));
        }

        $this->hydrateOptions($data);

        parent::hydrate($data);
    }
}
