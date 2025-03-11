<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\{Text, TextElement};

class RichTextPreformatted extends RichTextElement
{
    /**
     * @var TextElement[]
     */
    private array $elements = [];

    private ?int $border = null;

    /**
     * Add an element
     */
    public function addElement(TextElement $element): static
    {
        $this->elements[] = $element->setParent($this);

        return $this;
    }

    /**
     * Set a collection of elements
     *
     * @param TextElement[] $elements
     */
    public function setElements(array $elements): static
    {
        $this->elements = [];

        foreach ($elements as $element) {
            $this->addElement($element);
        }

        return $this;
    }

    /**
     * @return TextElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * Set text (for backward compatibility)
     */
    public function text(string $text): static
    {
        $textElement = new Text();
        $textElement->text($text);
        $this->elements = [$textElement->setParent($this)];

        return $this;
    }

    /**
     * Set the border
     */
    public function setBorder(int $border): static
    {
        $this->border = $border;

        return $this;
    }

    public function getElementType(): string
    {
        return 'rich_text_preformatted';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        // Empty elements array is allowed (according to the specification)

        foreach ($this->elements as $element) {
            $element->validate();
        }
    }

    /**
     * Convert the element to an array
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        $elements = [];
        foreach ($this->elements as $element) {
            $elements[] = $element->toArray();
        }

        $data['elements'] = $elements;

        if ($this->border !== null) {
            $data['border'] = $this->border;
        }

        return $data;
    }

    /**
     * Generate an element from an array
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        // Support for backward compatibility
        if ($data->has('text')) {
            $this->text($data->useValue('text'));
        }

        if ($data->has('border')) {
            $this->setBorder($data->useValue('border'));
        }

        if ($data->has('elements')) {
            $elements = $data->useArray('elements');
            foreach ($elements as $element) {
                if (!isset($element['type'])) {
                    throw new Exception('RichTextPreformatted element data must include a type');
                }

                $type = $element['type'];
                $textElement = TextElement::createFromType($type);
                $textElement->hydrate(new HydrationData($element));
                $this->addElement($textElement);
            }
        }
    }
}
