<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

class Confirm extends Element
{
    private ?PlainText $title = null;

    private ?Text $text = null;

    private ?PlainText $confirm = null;

    private ?PlainText $deny = null;

    public function __construct(
        ?string $title = null,
        ?string $text = null,
        ?string $confirm = null,
        ?string $deny = null,
    ) {
        if ($title !== null && $title !== '') {
            $this->title($title);
        }

        if ($text !== null && $text !== '') {
            $this->text($text);
        }

        $this->confirm($confirm ?? 'OK');
        $this->deny($deny ?? 'Cancel');
    }

    public function setTitle(PlainText $title): static
    {
        $this->title = $title->setParent($this);

        return $this;
    }

    public function setText(Text $text): static
    {
        $this->text = $text->setParent($this);

        return $this;
    }

    public function setConfirm(PlainText $confirm): static
    {
        $this->confirm = $confirm->setParent($this);

        return $this;
    }

    public function setDeny(PlainText $deny): static
    {
        $this->deny = $deny->setParent($this);

        return $this;
    }

    public function title(string $title): static
    {
        return $this->setTitle(new PlainText($title));
    }

    public function text(string $text): static
    {
        return $this->setText(new MrkdwnText($text));
    }

    public function confirm(string $confirm): static
    {
        return $this->setConfirm(new PlainText($confirm));
    }

    public function deny(string $deny): static
    {
        return $this->setDeny(new PlainText($deny));
    }

    public function validate(): void
    {
        if (
            $this->title === null
            || $this->confirm === null
            || $this->deny === null
            || $this->text === null
        ) {
            throw new Exception('Confirm must contain "title", "confirm", "text", "deny"');
        }

        $this->title->validate();
        $this->confirm->validate();
        $this->deny->validate();
        $this->text->validate();
    }

    public function toArray(): array
    {
        assert(
            $this->title !== null
            && $this->text !== null
            && $this->confirm !== null
            && $this->deny !== null,
        );

        return parent::toArray() + [
            'title' => $this->title->toArray(),
            'text' => $this->text->toArray(),
            'confirm' => $this->confirm->toArray(),
            'deny' => $this->deny->toArray(),
        ];
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('title')) {
            $this->setTitle(PlainText::fromArray($data->useElement('title')));
        }

        if ($data->has('text')) {
            $this->setText(Text::fromArray($data->useElement('text')));
        }

        if ($data->has('confirm')) {
            $this->setConfirm(PlainText::fromArray($data->useElement('confirm')));
        }

        if ($data->has('deny')) {
            $this->setDeny(PlainText::fromArray($data->useElement('deny')));
        }

        parent::hydrate($data);
    }
}
