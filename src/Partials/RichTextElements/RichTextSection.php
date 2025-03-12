<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\TextElement;

class RichTextSection extends RichTextElement
{
    /**
     * @var TextElement[]
     */
    private array $elements = [];

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

    public function getElementType(): string
    {
        return 'rich_text_section';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        // Empty elements array is allowed (according to Slack API specification)

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

        return $data;
    }

    /**
     * Generate an element from an array
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('elements')) {
            $elements = $data->useArray('elements');
            foreach ($elements as $element) {
                if (!isset($element['type'])) {
                    throw new Exception('RichTextSection element data must include a type');
                }

                $type = $element['type'];
                $textElement = TextElement::createFromType($type);
                $textElement->hydrate(new HydrationData($element));
                $this->addElement($textElement);
            }
        }
    }
}
