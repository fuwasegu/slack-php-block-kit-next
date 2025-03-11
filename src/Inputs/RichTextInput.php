<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Blocks\RichText;
use SlackPhp\BlockKit\Partials\{DispatchActionConfig, PlainText};
use SlackPhp\BlockKit\Partials\RichTextElements\RichTextElement;

class RichTextInput extends InputElement
{
    use HasPlaceholder;

    /**
     * RichTextInputの設定オプション
     */
    private ?bool $focusOnLoad = null;

    private ?DispatchActionConfig $dispatchActionConfig = null;

    private ?RichText $initialValue = null;

    /**
     * リッチテキスト入力の初期値を設定する
     *
     * @param RichText $richText リッチテキストブロック
     */
    public function initialValue(RichText $richText): static
    {
        $this->initialValue = $richText;

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

        if ($this->initialValue instanceof RichText) {
            $this->initialValue->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['type'] = 'rich_text_input';

        if ($this->placeholder instanceof PlainText) {
            $data['placeholder'] = $this->placeholder->toArray();
        }

        if ($this->focusOnLoad !== null) {
            $data['focus_on_load'] = $this->focusOnLoad;
        }

        if ($this->initialValue instanceof RichText) {
            $data['initial_value'] = array_map(
                static fn (RichTextElement $element): array => $element->toArray(),
                $this->initialValue->getElements(),
            );
        }

        if ($this->dispatchActionConfig instanceof DispatchActionConfig) {
            $data['dispatch_action_config'] = $this->dispatchActionConfig->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('focus_on_load')) {
            $this->focusOnLoad($data->useValue('focus_on_load'));
        }

        if ($data->has('placeholder')) {
            $this->setPlaceholder(PlainText::fromArray($data->useElement('placeholder')));
        }

        if ($data->has('initial_value') && is_array($data->get('initial_value'))) {
            $richText = new RichText();
            foreach ($data->useValue('initial_value') as $elementData) {
                $type = $elementData['type'] ?? null;
                if ($type) {
                    $element = RichTextElement::createFromType($type);
                    $element->hydrate(new HydrationData($elementData));
                    $richText->addElement($element);
                }
            }
            if ($richText->getElements() !== []) {
                $this->initialValue($richText);
            }
        }

        if ($data->has('dispatch_action_config')) {
            $this->setDispatchActionConfig(
                DispatchActionConfig::fromArray($data->useElement('dispatch_action_config')),
            );
        }

        parent::hydrate($data);
    }
}
