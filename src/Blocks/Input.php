<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\{Element,
    Exception,
    HydrationData,
    Inputs,
    Inputs\Checkboxes,
    Inputs\DatePicker,
    Inputs\NumberInput,
    Inputs\RadioButtons,
    Inputs\SelectMenus\MultiSelectMenuFactory,
    Inputs\SelectMenus\SelectMenu,
    Inputs\SelectMenus\SelectMenuFactory,
    Inputs\TextInput,
    Partials,
    Partials\PlainText,
    Type};

/**
 * A block that collects information from users.
 *
 * @see https://api.slack.com/reference/block-kit/blocks#input
 */
class Input extends BlockElement
{
    private ?PlainText $label = null;

    private ?Element $element = null;

    private ?PlainText $hint = null;

    private bool $optional = false;

    private bool $dispatchAction = false;

    public function __construct(?string $blockId = null, ?string $label = null, ?Element $element = null)
    {
        parent::__construct($blockId);

        if ($label !== null && $label !== '') {
            $this->label($label);
        }

        if ($element instanceof Element) {
            $this->setElement($element);
        }
    }

    public function setLabel(PlainText $label): static
    {
        $this->label = $label->setParent($this);

        return $this;
    }

    public function setElement(Element $element): static
    {
        if ($this->element instanceof Element) {
            throw new Exception('Input element already set as type %s', [$this->element->getType()]);
        }

        if (!in_array($element->getType(), Type::INPUT_ELEMENTS, true)) {
            throw new Exception('Invalid input element type: %s', [$element->getType()]);
        }

        $this->element = $element->setParent($this);

        return $this;
    }

    public function setHint(PlainText $hint): static
    {
        $this->hint = $hint->setParent($this);

        return $this;
    }

    public function label(string $text, ?bool $emoji = null): static
    {
        return $this->setLabel(new PlainText($text, $emoji));
    }

    public function hint(string $text, ?bool $emoji = null): static
    {
        return $this->setHint(new PlainText($text, $emoji));
    }

    public function optional(bool $optional = true): static
    {
        $this->optional = $optional;

        return $this;
    }

    public function dispatchAction(bool $dispatchAction = true): static
    {
        $this->dispatchAction = $dispatchAction;

        return $this;
    }

    public function newDatePicker(?string $actionId = null): DatePicker
    {
        $action = new DatePicker($actionId);
        $this->setElement($action);

        return $action;
    }

    public function newSelectMenu(?string $actionId = null): SelectMenuFactory
    {
        return new SelectMenuFactory($actionId, function (SelectMenu $menu): void {
            $this->setElement($menu);
        });
    }

    public function newMultiSelectMenu(?string $actionId = null): MultiSelectMenuFactory
    {
        return new MultiSelectMenuFactory($actionId, function (SelectMenu $menu): void {
            $this->setElement($menu);
        });
    }

    public function newTextInput(?string $actionId = null): TextInput
    {
        $action = new TextInput($actionId);
        $this->setElement($action);

        return $action;
    }

    public function newNumberInput(?string $actionId = null): NumberInput
    {
        $action = new NumberInput($actionId);
        $this->setElement($action);

        return $action;
    }

    public function newRadioButtons(?string $actionId = null): RadioButtons
    {
        $action = new RadioButtons($actionId);
        $this->setElement($action);

        return $action;
    }

    public function newCheckboxes(?string $actionId = null): Checkboxes
    {
        $action = new Checkboxes($actionId);
        $this->setElement($action);

        return $action;
    }

    public function validate(): void
    {
        if (!$this->label instanceof PlainText) {
            throw new Exception('Input must contain a "label"');
        }

        if (!$this->element instanceof Element) {
            throw new Exception('Input must contain an "element"');
        }

        $this->label->validate();
        $this->element->validate();

        if ($this->hint instanceof PlainText) {
            $this->hint->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->label instanceof PlainText) {
            $data['label'] = $this->label->toArray();
        }

        if ($this->element instanceof Element) {
            $data['element'] = $this->element->toArray();
        }

        if ($this->hint instanceof PlainText) {
            $data['hint'] = $this->hint->toArray();
        }

        if ($this->optional) {
            $data['optional'] = $this->optional;
        }

        if ($this->dispatchAction) {
            $data['dispatch_action'] = $this->dispatchAction;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('label')) {
            $this->setLabel(PlainText::fromArray($data->useElement('label')));
        }

        if ($data->has('element')) {
            $this->setElement(Inputs\InputElement::fromArray($data->useElement('element')));
        }

        if ($data->has('hint')) {
            $this->setHint(PlainText::fromArray($data->useElement('hint')));
        }

        if ($data->has('optional')) {
            $this->optional($data->useValue('optional'));
        }

        if ($data->has('dispatch_action')) {
            $this->dispatchAction($data->useValue('dispatch_action'));
        }

        parent::hydrate($data);
    }
}
