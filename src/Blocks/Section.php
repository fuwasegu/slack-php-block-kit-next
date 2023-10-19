<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\{Element,
    Exception,
    HydrationData,
    Inputs,
    Inputs\Button,
    Inputs\DatePicker,
    Inputs\OverflowMenu,
    Inputs\RadioButtons,
    Inputs\SelectMenus\MultiSelectMenuFactory,
    Inputs\SelectMenus\SelectMenu,
    Inputs\SelectMenus\SelectMenuFactory,
    Inputs\TextInput,
    Kit,
    Partials,
    Partials\Fields,
    Partials\MrkdwnText,
    Partials\PlainText,
    Partials\Text,
    Type};

class Section extends BlockElement
{
    private ?Text $text = null;

    private ?Fields $fields = null;

    private ?Element $accessory = null;

    public function __construct(?string $blockId = null, ?string $text = null)
    {
        parent::__construct($blockId);

        if ($text !== null && $text !== '') {
            $this->mrkdwnText($text);
        }
    }

    public function setText(Text $text): static
    {
        $this->text = $text->setParent($this);

        return $this;
    }

    public function setFields(Fields $fields): static
    {
        $this->fields = $fields->setParent($this);

        return $this;
    }

    public function setAccessory(Element $accessory): static
    {
        if ($this->accessory instanceof Element) {
            throw new Exception('Section accessory already set as type %s', [$this->accessory->getType()]);
        }

        if (!in_array($accessory->getType(), Type::ACCESSORY_ELEMENTS, true)) {
            throw new Exception('Invalid section accessory type: %s', [$accessory->getType()]);
        }

        $this->accessory = $accessory->setParent($this);

        return $this;
    }

    public function plainText(string $text, ?bool $emoji = null): static
    {
        return $this->setText(new PlainText($text, $emoji));
    }

    public function mrkdwnText(string $text, ?bool $verbatim = null): static
    {
        return $this->setText(new MrkdwnText($text, $verbatim));
    }

    public function code(string $code): static
    {
        return $this->mrkdwnText(Kit::formatter()->codeBlock($code), true);
    }

    /**
     * @param string[] $values
     */
    public function fieldList(array $values): static
    {
        return $this->setFields(new Fields($values));
    }

    public function fieldMap(array $keyValuePairs): static
    {
        $fields = new Fields();
        foreach ($keyValuePairs as $key => $value) {
            $fields->add(new MrkdwnText($key));
            $fields->add(new MrkdwnText($value));
        }

        return $this->setFields($fields);
    }

    public function newImageAccessory(): Image
    {
        $accessory = new Image();
        $this->setAccessory($accessory);

        return $accessory;
    }

    public function newButtonAccessory(?string $actionId = null): Button
    {
        $accessory = new Button($actionId);
        $this->setAccessory($accessory);

        return $accessory;
    }

    public function newDatePickerAccessory(?string $actionId = null): DatePicker
    {
        $action = new DatePicker($actionId);
        $this->setAccessory($action);

        return $action;
    }

    public function newSelectMenuAccessory(?string $actionId = null): SelectMenuFactory
    {
        return new SelectMenuFactory($actionId, function (SelectMenu $menu): void {
            $this->setAccessory($menu);
        });
    }

    public function newMultiSelectMenuAccessory(?string $actionId = null): MultiSelectMenuFactory
    {
        return new MultiSelectMenuFactory($actionId, function (SelectMenu $menu): void {
            $this->setAccessory($menu);
        });
    }

    public function newTextInputAccessory(?string $actionId = null): TextInput
    {
        $action = new TextInput($actionId);
        $this->setAccessory($action);

        return $action;
    }

    public function newRadioButtonsAccessory(?string $actionId = null): RadioButtons
    {
        $action = new RadioButtons($actionId);
        $this->setAccessory($action);

        return $action;
    }

    public function newCheckboxesAccessory(?string $actionId = null): Inputs\Checkboxes
    {
        $action = new Inputs\Checkboxes($actionId);
        $this->setAccessory($action);

        return $action;
    }

    public function newOverflowMenuAccessory(?string $actionId = null): OverflowMenu
    {
        $action = new OverflowMenu($actionId);
        $this->setAccessory($action);

        return $action;
    }

    public function validate(): void
    {
        if (!$this->text instanceof Text && !$this->fields instanceof Fields) {
            throw new Exception('Section must contain at least a "text" or "fields" item');
        }

        if ($this->text instanceof Text) {
            $this->text->validate();
        }

        if ($this->fields instanceof Fields) {
            $this->fields->validate();
        }

        if ($this->accessory instanceof Element) {
            $this->accessory->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->text instanceof Text) {
            $data['text'] = $this->text->toArray();
        }

        if ($this->fields instanceof Fields) {
            $data['fields'] = $this->fields->toArray();
        }

        if ($this->accessory instanceof Element) {
            $data['accessory'] = $this->accessory->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('text')) {
            $this->setText(Text::fromArray($data->useElement('text')));
        }

        if ($data->has('fields')) {
            $this->setFields(Fields::fromArray($data->useElements('fields')));
        }

        if ($data->has('accessory')) {
            $this->setAccessory(Inputs\InputElement::fromArray($data->useElement('accessory')));
        }

        parent::hydrate($data);
    }
}
