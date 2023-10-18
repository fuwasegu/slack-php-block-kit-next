<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

class DispatchActionConfig extends Element
{
    private const ON_ENTER_PRESSED = 'on_enter_pressed';
    private const ON_CHARACTER_ENTERED = 'on_character_entered';

    /**
     * @var string[]|array
     */
    private array $triggerActionsOn = [];

    public function triggerActionsOn(string $eventType): static
    {
        $this->triggerActionsOn[] = $eventType;

        return $this;
    }

    /**
     * @return static
     */
    public function triggerActionsOnEnterPressed()
    {
        return $this->triggerActionsOn(self::ON_ENTER_PRESSED);
    }

    /**
     * @return static
     */
    public function triggerActionsOnCharacterEntered()
    {
        return $this->triggerActionsOn(self::ON_CHARACTER_ENTERED);
    }

    public function validate(): void
    {
        if (empty($this->triggerActionsOn)) {
            throw new Exception('DispatchActionConfig must have at least one triggerActionsOn set');
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['trigger_actions_on'] = $this->triggerActionsOn;

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('trigger_actions_on')) {
            $this->triggerActionsOn = $data->useArray('trigger_actions_on');
        }

        parent::hydrate($data);
    }
}
