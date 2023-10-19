<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Filter;

class MultiConversationSelectMenu extends MultiSelectMenu
{
    /**
     * @var string[]
     */
    private array $initialConversations = [];

    private bool $defaultToCurrentConversation = false;

    private ?Filter $filter = null;

    /**
     * @param string[] $initialConversations
     */
    public function initialConversations(array $initialConversations): static
    {
        $this->initialConversations = $initialConversations;

        return $this;
    }

    public function defaultToCurrentConversation(bool $enabled): static
    {
        $this->defaultToCurrentConversation = $enabled;

        return $this;
    }

    public function setFilter(Filter $filter): static
    {
        $this->filter = $filter->setParent($this);

        return $this;
    }

    public function newFilter(): Filter
    {
        $filter = Filter::new();
        $this->setFilter($filter);

        return $filter;
    }

    public function validate(): void
    {
        parent::validate();

        if ($this->filter instanceof Filter) {
            $this->filter->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->initialConversations !== []) {
            $data['initial_conversations'] = $this->initialConversations;
        }

        if ($this->defaultToCurrentConversation) {
            $data['default_to_current_conversation'] = $this->defaultToCurrentConversation;
        }

        if ($this->filter instanceof Filter) {
            $data['filter'] = $this->filter->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_conversations')) {
            $this->initialConversations($data->useArray('initial_conversations'));
        }

        if ($data->has('default_to_current_conversation')) {
            $this->defaultToCurrentConversation($data->useValue('default_to_current_conversation'));
        }

        if ($data->has('filter')) {
            $this->setFilter(Filter::fromArray($data->useElement('filter')));
        }

        parent::hydrate($data);
    }
}
