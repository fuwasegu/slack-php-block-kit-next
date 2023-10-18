<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\{DispatchActionConfig, PlainText};

class NumberInput extends InputElement
{
    use HasPlaceholder;

    private bool $isDecimalAllowed = false;

    /**
     * @var int|float
     */
    private $initialValue;

    /**
     * @var int|float
     */
    private $minValue;

    /**
     * @var int|float
     */
    private $maxValue;

    private ?\SlackPhp\BlockKit\Partials\DispatchActionConfig $dispatchActionConfig = null;

    private ?bool $focusOnLoad = null;

    public function setIsDecimalAllowed(bool $flag): static
    {
        $this->isDecimalAllowed = $flag;

        return $this;
    }

    /**
     * @param int|float $value
     */
    public function setInitialValue($value): static
    {
        $this->initialValue = $value;

        return $this;
    }

    /**
     * @param int|float $value
     */
    public function setMinValue($value): static
    {
        $this->minValue = $value;

        return $this;
    }

    /**
     * @param int|float $value
     */
    public function setMaxValue($value): static
    {
        $this->maxValue = $value;

        return $this;
    }

    public function setDispatchActionConfig(DispatchActionConfig $config): static
    {
        $this->dispatchActionConfig = $config;

        return $this;
    }

    public function setFocusOnLoad(bool $flag): static
    {
        $this->focusOnLoad = $flag;

        return $this;
    }

    public function validate(): void
    {
        if (!empty($this->placeholder)) {
            $this->placeholder->validate();
        }

        if (
            $this->minValue !== null && $this->maxValue !== null

            && $this->minValue > $this->maxValue
        ) {
            throw new Exception('Number input max value must be greater than min value');
        }

        if ($this->isDecimalAllowed && !is_int($this->initialValue)) {
            throw new Exception('The initial value must be only decimal number when is_decimal_allowed is true');
        }

        if (
            $this->maxValue !== null && $this->initialValue !== null

            && $this->maxValue <= $this->initialValue
        ) {
            throw new Exception('The initial value must be less than or equal to max_value');
        }

        if (
            $this->minValue !== null && $this->initialValue !== null

            && $this->initialValue <= $this->minValue
        ) {
            throw new Exception('The initial value must be greater than or equal to min_value');
        }

        if ($this->dispatchActionConfig instanceof \SlackPhp\BlockKit\Partials\DispatchActionConfig) {
            $this->dispatchActionConfig->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (!empty($this->placeholder)) {
            $data['placeholder'] = $this->placeholder->toArray();
        }

        if (!empty($this->initialValue)) {
            // Must be a String in SlackAPI documentation
            $data['initial_value'] = (string)$this->initialValue;
        }

        if ($this->minValue !== null) {
            // Must be a String in SlackAPI documentation
            $data['min_value'] = (string)$this->minValue;
        }

        if ($this->maxValue !== null) {
            // Must be a String in SlackAPI documentation
            $data['max_value'] = (string)$this->maxValue;
        }

        if ($this->dispatchActionConfig instanceof \SlackPhp\BlockKit\Partials\DispatchActionConfig) {
            $data['dispatch_action_config'] = $this->dispatchActionConfig->toArray();
        }

        $data['is_decimal_allowed'] = $this->isDecimalAllowed;

        if ($this->focusOnLoad !== null) {
            $data['focus_on_load'] = $this->focusOnLoad;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('placeholder')) {
            $this->setPlaceholder(PlainText::fromArray($data->useElement('placeholder')));
        }

        if ($data->has('initial_value')) {
            $this->setInitialValue($data->useValue('initial_value'));
        }

        if ($data->has('min_value')) {
            $this->setMinValue($data->useValue('min_value'));
        }

        if ($data->has('max_value')) {
            $this->setMaxValue($data->useValue('max_value'));
        }

        if ($data->has('dispatch_action_config')) {
            $this->setDispatchActionConfig(
                DispatchActionConfig::fromArray($data->useElement('dispatch_action_config')),
            );
        }

        if ($data->has('is_decimal_allowed')) {
            $this->setIsDecimalAllowed($data->useValue('is_decimal_allowed'));
        }

        if ($data->has('focus_on_load')) {
            $this->setFocusOnLoad($data->useValue('focus_on_load'));
        }

        parent::hydrate($data);
    }
}
