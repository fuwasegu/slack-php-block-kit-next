<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class RichTextList extends RichTextElement
{
    /**
     * @var RichTextSection[]
     */
    private array $elements = [];

    private ?string $style = null;

    private ?int $indent = null;

    private ?int $offset = null;

    private ?int $border = null;

    /**
     * Add an element
     */
    public function addElement(RichTextSection $element): static
    {
        $this->elements[] = $element->setParent($this);

        return $this;
    }

    /**
     * Set a collection of elements
     *
     * @param RichTextSection[] $elements
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
     * @return RichTextSection[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * Set the list style
     */
    public function setStyle(string $style): static
    {
        if (!in_array($style, ['bullet', 'ordered'], true)) {
            throw new Exception('Invalid list style: %s', [$style]);
        }

        $this->style = $style;

        return $this;
    }

    /**
     * Set bullet style
     */
    public function bullet(): static
    {
        return $this->setStyle('bullet');
    }

    /**
     * Set ordered style
     */
    public function ordered(): static
    {
        return $this->setStyle('ordered');
    }

    /**
     * Set the indent level
     */
    public function setIndent(int $indent): static
    {
        $this->indent = $indent;

        return $this;
    }

    /**
     * Set the offset
     */
    public function setOffset(int $offset): static
    {
        $this->offset = $offset;

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
        return 'rich_text_list';
    }

    /**
     * Validate the element
     */
    public function validate(): void
    {
        if ($this->style === null) {
            throw new Exception('RichTextList must have a style');
        }

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
        $data['style'] = $this->style;

        if ($this->indent !== null) {
            $data['indent'] = $this->indent;
        }

        if ($this->offset !== null) {
            $data['offset'] = $this->offset;
        }

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

        if ($data->has('style')) {
            $this->setStyle($data->useValue('style'));
        } else {
            // Style is a required attribute, so throw an error if it doesn't exist
            throw new Exception('RichTextList must have a style');
        }

        if ($data->has('indent')) {
            $this->setIndent($data->useValue('indent'));
        }

        if ($data->has('offset')) {
            $this->setOffset($data->useValue('offset'));
        }

        if ($data->has('border')) {
            $this->setBorder($data->useValue('border'));
        }

        if ($data->has('elements')) {
            $elements = $data->useArray('elements');
            foreach ($elements as $element) {
                if (!isset($element['type'])) {
                    throw new Exception('RichTextList element data must include a type');
                }

                if ($element['type'] !== 'rich_text_section') {
                    throw new Exception('RichTextList elements must be of type rich_text_section');
                }

                $section = new RichTextSection();
                $section->hydrate(new HydrationData($element));
                $this->addElement($section);
            }
        }
    }
}
