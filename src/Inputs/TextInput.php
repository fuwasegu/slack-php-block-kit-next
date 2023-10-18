<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\{DispatchActionConfig, PlainText};

class TextInput extends InputElement
{
    use HasPlaceholder;

    private const MAX_MIN_LENGTH = 3000;

    /**
     * @var string
     */
    private $initialValue;

    /**
     * @var bool
     */
    private $multiline;

    /**
     * @var int
     */
    private $minLength;

    /**
     * @var int
     */
    private $maxLength;

    /**
     * @var DispatchActionConfig
     */
    private $dispatchActionConfig;

    /**
     * @return static
     */
    public function initialValue(string $text)
    {
        $this->initialValue = $text;

        return $this;
    }

    /**
     * @return static
     */
    public function multiline(bool $flag)
    {
        $this->multiline = $flag;

        return $this;
    }

    /**
     * @return static
     */
    public function minLength(int $length)
    {
        if ($length < 0) {
            throw new Exception('Min length must be >= 0');
        }

        $this->minLength = $length;

        return $this;
    }

    /**
     * @return static
     */
    public function maxLength(int $length)
    {
        if ($length < 1) {
            throw new Exception('Max length must be >= 1');
        }

        $this->maxLength = $length;

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
    public function triggerActionOnEnterPressed()
    {
        $config = $this->dispatchActionConfig ?? DispatchActionConfig::new();
        $config->triggerActionsOnEnterPressed();

        return $this->setDispatchActionConfig($config);
    }

    /**
     * @return static
     */
    public function triggerActionOnCharacterEntered()
    {
        $config = $this->dispatchActionConfig ?? DispatchActionConfig::new();
        $config->triggerActionsOnCharacterEntered();

        return $this->setDispatchActionConfig($config);
    }

    public function validate(): void
    {
        if (!empty($this->placeholder)) {
            $this->placeholder->validate();
        }

        if (isset($this->minLength)) {
            if ($this->minLength > self::MAX_MIN_LENGTH) {
                throw new Exception('Text input min length cannot exceed %d', [self::MAX_MIN_LENGTH]);
            }

            if (isset($this->maxLength) && $this->maxLength <= $this->minLength) {
                throw new Exception('Text input max length must be greater than min length');
            }
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
            $data['initial_value'] = $this->initialValue;
        }

        if (isset($this->multiline)) {
            $data['multiline'] = $this->multiline;
        }

        if (isset($this->minLength)) {
            $data['min_length'] = $this->minLength;
        }

        if (isset($this->maxLength)) {
            $data['max_length'] = $this->maxLength;
        }

        if (isset($this->dispatchActionConfig)) {
            $data['dispatch_action_config'] = $this->dispatchActionConfig->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_value')) {
            $this->initialValue($data->useValue('initial_value'));
        }

        if ($data->has('multiline')) {
            $this->initialValue($data->useValue('multiline'));
        }

        if ($data->has('min_length')) {
            $this->minLength($data->useValue('min_length'));
        }

        if ($data->has('max_length')) {
            $this->maxLength($data->useValue('max_length'));
        }

        if ($data->has('placeholder')) {
            $this->setPlaceholder(PlainText::fromArray($data->useElement('placeholder')));
        }

        if ($data->has('dispatch_action_config')) {
            $this->setDispatchActionConfig(
                DispatchActionConfig::fromArray($data->useElement('dispatch_action_config')),
            );
        }

        parent::hydrate($data);
    }
}
