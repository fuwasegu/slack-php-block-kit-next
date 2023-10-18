<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

class Filter extends Element
{
    private const CONVERSATION_TYPE_IM = 'im';
    private const CONVERSATION_TYPE_MPIM = 'mpim';
    private const CONVERSATION_TYPE_PRIVATE = 'private';
    private const CONVERSATION_TYPE_PUBLIC = 'public';

    /**
     * @var string[]|array
     */
    private array $include = [];

    private ?bool $excludeExternalSharedChannels = null;

    private ?bool $excludeBotUsers = null;

    public function includeType(string $conversationType): static
    {
        $this->include[] = $conversationType;

        return $this;
    }

    /**
     * @param string[] $conversationTypes
     */
    public function includeTypes(array $conversationTypes): static
    {
        $this->include = $conversationTypes;

        return $this;
    }

    public function includeIm(): static
    {
        return $this->includeType(self::CONVERSATION_TYPE_IM);
    }

    public function includeMpim(): static
    {
        return $this->includeType(self::CONVERSATION_TYPE_MPIM);
    }

    public function includePrivate(): static
    {
        return $this->includeType(self::CONVERSATION_TYPE_PRIVATE);
    }

    public function includePublic(): static
    {
        return $this->includeType(self::CONVERSATION_TYPE_PUBLIC);
    }

    public function excludeBotUsers(bool $excludeBotUsers): static
    {
        $this->excludeBotUsers = $excludeBotUsers;

        return $this;
    }

    public function excludeExternalSharedChannels(bool $excludeExternalSharedChannels): static
    {
        $this->excludeExternalSharedChannels = $excludeExternalSharedChannels;

        return $this;
    }

    public function validate(): void
    {
        if ($this->include === [] && $this->excludeExternalSharedChannels === null && $this->excludeBotUsers === null) {
            throw new Exception('Filter must have at least one property set');
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->include !== []) {
            $data['include'] = $this->include;
        }

        if ($this->excludeExternalSharedChannels !== null) {
            $data['exclude_external_shared_channels'] = $this->excludeExternalSharedChannels;
        }

        if ($this->excludeBotUsers !== null) {
            $data['exclude_bot_users'] = $this->excludeBotUsers;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('include')) {
            $this->includeTypes($data->useArray('include'));
        }

        if ($data->has('exclude_external_shared_channels')) {
            $this->excludeExternalSharedChannels($data->useValue('exclude_external_shared_channels'));
        }

        if ($data->has('exclude_bot_users')) {
            $this->excludeBotUsers($data->useValue('exclude_bot_users'));
        }

        parent::hydrate($data);
    }
}
