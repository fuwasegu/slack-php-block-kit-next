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

    private ?string $initialValue = null;

    private ?bool $multiline = null;

    private ?int $minLength = null;

    private ?int $maxLength = null;

    private ?DispatchActionConfig $dispatchActionConfig = null;

    public function initialValue(string $text): static
    {
        $this->initialValue = $text;

        return $this;
    }

    public function multiline(bool $flag): static
    {
        $this->multiline = $flag;

        return $this;
    }

    public function minLength(int $length): static
    {
        if ($length < 0) {
            throw new Exception('Min length must be >= 0');
        }

        $this->minLength = $length;

        return $this;
    }

    public function maxLength(int $length): static
    {
        if ($length < 1) {
            throw new Exception('Max length must be >= 1');
        }

        $this->maxLength = $length;

        return $this;
    }

    public function setDispatchActionConfig(DispatchActionConfig $config): static
    {
        $this->dispatchActionConfig = $config;

        return $this;
    }

    public function triggerActionOnEnterPressed(): static
    {
        $config = $this->dispatchActionConfig ?? DispatchActionConfig::new();
        $config->triggerActionsOnEnterPressed();

        return $this->setDispatchActionConfig($config);
    }

    public function triggerActionOnCharacterEntered(): static
    {
        $config = $this->dispatchActionConfig ?? DispatchActionConfig::new();
        $config->triggerActionsOnCharacterEntered();

        return $this->setDispatchActionConfig($config);
    }

    public function validate(): void
    {
        if ($this->placeholder instanceof PlainText) {
            $this->placeholder->validate();
        }

        if ($this->minLength !== null) {
            if ($this->minLength > self::MAX_MIN_LENGTH) {
                throw new Exception('Text input min length cannot exceed %d', [self::MAX_MIN_LENGTH]);
            }

            if ($this->maxLength !== null && $this->maxLength <= $this->minLength) {
                throw new Exception('Text input max length must be greater than min length');
            }
        }

        if ($this->dispatchActionConfig instanceof DispatchActionConfig) {
            $this->dispatchActionConfig->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->placeholder instanceof PlainText) {
            $data['placeholder'] = $this->placeholder->toArray();
        }

        if ($this->initialValue !== null && $this->initialValue !== '') {
            $data['initial_value'] = $this->initialValue;
        }

        if ($this->multiline !== null) {
            $data['multiline'] = $this->multiline;
        }

        if ($this->minLength !== null) {
            $data['min_length'] = $this->minLength;
        }

        if ($this->maxLength !== null) {
            $data['max_length'] = $this->maxLength;
        }

        if ($this->dispatchActionConfig instanceof DispatchActionConfig) {
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
