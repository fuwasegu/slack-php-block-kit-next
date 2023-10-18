<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\{DispatchActionConfig, PlainText};

class NumberInput extends InputElement
{
    use HasPlaceholder;

    /**
     * @var bool
     */
    private $isDecimalAllowed = false;

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

    /**
     * @var DispatchActionConfig
     */
    private $dispatchActionConfig;

    /**
     * @var bool
     */
    private $focusOnLoad;

    /**
     * @return static
     */
    public function setIsDecimalAllowed(bool $flag)
    {
        $this->isDecimalAllowed = $flag;

        return $this;
    }

    /**
     * @param  int|float $value
     * @return static
     */
    public function setInitialValue($value)
    {
        $this->initialValue = $value;

        return $this;
    }

    /**
     * @param  int|float $value
     * @return static
     */
    public function setMinValue($value)
    {
        $this->minValue = $value;

        return $this;
    }

    /**
     * @param  int|float $value
     * @return static
     */
    public function setMaxValue($value)
    {
        $this->maxValue = $value;

        return $this;
    }

    /**
     * @return static
     */
    public function setDispatchActionConfig(DispatchActionConfig $config)
    {
        $this->dispatchActionConfig = $config;

        return $this;
    }

    /**
     * @return static
     */
    public function setFocusOnLoad(bool $flag)
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
            isset($this->minValue, $this->maxValue)

            && $this->minValue > $this->maxValue
        ) {
            throw new Exception('Number input max value must be greater than min value');
        }

        if ($this->isDecimalAllowed && !is_int($this->initialValue)) {
            throw new Exception('The initial value must be only decimal number when is_decimal_allowed is true');
        }

        if (
            isset($this->maxValue, $this->initialValue)

            && $this->maxValue <= $this->initialValue
        ) {
            throw new Exception('The initial value must be less than or equal to max_value');
        }

        if (
            isset($this->minValue, $this->initialValue)

            && $this->initialValue <= $this->minValue
        ) {
            throw new Exception('The initial value must be greater than or equal to min_value');
        }

        if (isset($this->dispatchActionConfig)) {
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
            $data['initial_value'] = (string) $this->initialValue;
        }

        if (isset($this->minValue)) {
            // Must be a String in SlackAPI documentation
            $data['min_value'] = (string) $this->minValue;
        }

        if (isset($this->maxValue)) {
            // Must be a String in SlackAPI documentation
            $data['max_value'] = (string) $this->maxValue;
        }

        if (isset($this->dispatchActionConfig)) {
            $data['dispatch_action_config'] = $this->dispatchActionConfig->toArray();
        }

        if (isset($this->isDecimalAllowed)) {
            $data['is_decimal_allowed'] = $this->isDecimalAllowed;
        }

        if (isset($this->focusOnLoad)) {
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
