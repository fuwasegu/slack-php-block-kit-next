<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\{DispatchActionConfig, PlainText};

class RichTextInput extends InputElement
{
    use HasPlaceholder;

    /**
     * @var string|null
     */
    private $initialValue;

    /**
     * @var bool|null
     */
    private $focusOnLoad;

    /**
     * @var DispatchActionConfig|null
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
    public function focusOnLoad(bool $flag)
    {
        $this->focusOnLoad = $flag;

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

        if (isset($this->dispatchActionConfig)) {
            $this->dispatchActionConfig->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['type'] = 'rich_text_input';
        $data['multiline'] = true;

        if (!empty($this->placeholder)) {
            $data['placeholder'] = $this->placeholder->toArray();
        }

        if (!empty($this->initialValue)) {
            $data['initial_value'] = $this->initialValue;
        }

        if (isset($this->focusOnLoad)) {
            $data['focus_on_load'] = $this->focusOnLoad;
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
