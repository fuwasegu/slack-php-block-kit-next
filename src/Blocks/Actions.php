<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\{Element, Exception, HydrationData, Inputs, Type};
use SlackPhp\BlockKit\Inputs\InputElement;

class Actions extends BlockElement
{
    private const MAX_ACTIONS = 5;

    /**
     * @var Element[]
     */
    private array $elements = [];

    /**
     * @param Element[] $elements
     */
    public function __construct(?string $blockId = null, array $elements = [])
    {
        parent::__construct($blockId);
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    public function add(Element $element): static
    {
        if (!in_array($element->getType(), Type::ACTION_ELEMENTS, true)) {
            throw new Exception('Invalid actions element type: %s', [$element->getType()]);
        }

        if (count($this->elements) >= self::MAX_ACTIONS) {
            throw new Exception('Context cannot have more than %d elements', [self::MAX_ACTIONS]);
        }

        $this->elements[] = $element->setParent($this);

        return $this;
    }

    public function newButton(?string $actionId = null): Inputs\Button
    {
        $action = new Inputs\Button($actionId);
        $this->add($action);

        return $action;
    }

    public function newDatePicker(?string $actionId = null): Inputs\DatePicker
    {
        $action = new Inputs\DatePicker($actionId);
        $this->add($action);

        return $action;
    }

    public function newSelectMenu(?string $actionId = null): Inputs\SelectMenus\SelectMenuFactory
    {
        return new Inputs\SelectMenus\SelectMenuFactory($actionId, function (Inputs\SelectMenus\SelectMenu $menu): void {
            $this->add($menu);
        });
    }

    public function newMultiSelectMenu(?string $actionId = null): Inputs\SelectMenus\MultiSelectMenuFactory
    {
        return new Inputs\SelectMenus\MultiSelectMenuFactory($actionId, function (Inputs\SelectMenus\SelectMenu $menu): void {
            $this->add($menu);
        });
    }

    public function newTextInput(?string $actionId = null): Inputs\TextInput
    {
        $action = new Inputs\TextInput($actionId);
        $this->add($action);

        return $action;
    }

    public function newRadioButtons(?string $actionId = null): Inputs\RadioButtons
    {
        $action = new Inputs\RadioButtons($actionId);
        $this->add($action);

        return $action;
    }

    public function newCheckboxes(?string $actionId = null): Inputs\Checkboxes
    {
        $action = new Inputs\Checkboxes($actionId);
        $this->add($action);

        return $action;
    }

    public function newOverflowMenu(?string $actionId = null): Inputs\OverflowMenu
    {
        $action = new Inputs\OverflowMenu($actionId);
        $this->add($action);

        return $action;
    }

    public function validate(): void
    {
        if ($this->elements === []) {
            throw new Exception('Context must contain at least one element');
        }

        $actionIds = [];
        foreach ($this->elements as $element) {
            $element->validate();
            if ($element instanceof InputElement && $element->getActionId() !== null) {
                $actionIds[] = $element->getActionId();
            }
        }

        $actionIdArrayCount = array_count_values($actionIds);
        if ($actionIdArrayCount !== []) {
            $duplicateActionIds = [];
            foreach ($actionIdArrayCount as $key => $value) {
                if ($value > 1) {
                    $duplicateActionIds[] = $key;
                }
            }

            if ($duplicateActionIds !== []) {
                throw new Exception(
                    'The following action_ids are duplicated : ' . implode(', ', $duplicateActionIds) . ' ]',
                );
            }
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        $data['elements'] = [];
        foreach ($this->elements as $element) {
            $data['elements'][] = $element->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        foreach ($data->useElements('elements') as $element) {
            $this->add(Inputs\InputElement::fromArray($element));
        }

        parent::hydrate($data);
    }
}
