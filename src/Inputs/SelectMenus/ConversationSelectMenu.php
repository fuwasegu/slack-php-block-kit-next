<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs\SelectMenus;

use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Filter;

class ConversationSelectMenu extends SelectMenu
{
    private ?string $initialConversation = null;

    private bool $responseUrlEnabled = false;

    private bool $defaultToCurrentConversation = false;

    private ?Filter $filter = null;

    public function initialConversation(string $initialConversation): static
    {
        $this->initialConversation = $initialConversation;

        return $this;
    }

    public function responseUrlEnabled(bool $enabled): static
    {
        $this->responseUrlEnabled = $enabled;

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

        if (is_string($this->initialConversation) && $this->initialConversation !== '') {
            $data['initial_conversation'] = $this->initialConversation;
        }

        if ($this->responseUrlEnabled === true) {
            $data['response_url_enabled'] = $this->responseUrlEnabled;
        }

        if ($this->defaultToCurrentConversation === true) {
            $data['default_to_current_conversation'] = $this->defaultToCurrentConversation;
        }

        if ($this->filter instanceof Filter) {
            $data['filter'] = $this->filter->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_conversation')) {
            $this->initialConversation($data->useValue('initial_conversation'));
        }

        if ($data->has('response_url_enabled')) {
            $this->responseUrlEnabled($data->useValue('response_url_enabled'));
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
