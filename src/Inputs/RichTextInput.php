<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\{DispatchActionConfig, PlainText};

class RichTextInput extends InputElement
{
    use HasPlaceholder;

    private ?string $initialValue = null;

    private ?bool $focusOnLoad = null;

    private ?DispatchActionConfig $dispatchActionConfig = null;

    public function initialValue(string $text): static
    {
        $this->initialValue = $text;

        return $this;
    }

    public function focusOnLoad(bool $flag): static
    {
        $this->focusOnLoad = $flag;

        return $this;
    }

    public function setDispatchActionConfig(DispatchActionConfig $config): static
    {
        $this->dispatchActionConfig = $config;

        return $this;
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

        if ($this->dispatchActionConfig instanceof DispatchActionConfig) {
            $this->dispatchActionConfig->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['type'] = 'rich_text_input';
        $data['multiline'] = true;

        if ($this->placeholder instanceof PlainText) {
            $data['placeholder'] = $this->placeholder->toArray();
        }

        if ($this->initialValue !== null && $this->initialValue !== '') {
            $data['initial_value'] = $this->initialValue;
        }

        if ($this->focusOnLoad !== null) {
            $data['focus_on_load'] = $this->focusOnLoad;
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

        if ($data->has('focus_on_load')) {
            $this->focusOnLoad($data->useValue('focus_on_load'));
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
