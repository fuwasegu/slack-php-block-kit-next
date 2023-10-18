<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

class Confirm extends Element
{
    /**
     * @var PlainText
     */
    private $title;

    /**
     * @var Text
     */
    private $text;

    /**
     * @var PlainText
     */
    private $confirm;

    /**
     * @var PlainText
     */
    private $deny;

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

    /**
     * @return static
     */
    public function title(string $title)
    {
        return $this->setTitle(new PlainText($title));
    }

    /**
     * @return static
     */
    public function text(string $text)
    {
        return $this->setText(new MrkdwnText($text));
    }

    /**
     * @return static
     */
    public function confirm(string $confirm)
    {
        return $this->setConfirm(new PlainText($confirm));
    }

    /**
     * @return static
     */
    public function deny(string $deny)
    {
        return $this->setDeny(new PlainText($deny));
    }

    public function validate(): void
    {
        if (empty($this->title)) {
            throw new Exception('Confirm component must have a "title" value');
        }

        if (empty($this->text)) {
            throw new Exception('Confirm component must have a "text" value');
        }

        if (empty($this->confirm)) {
            throw new Exception('Confirm component must have a "confirm" value');
        }

        if (empty($this->deny)) {
            throw new Exception('Confirm component must have a "deny" value');
        }

        $this->title->validate();
        $this->text->validate();
        $this->confirm->validate();
        $this->deny->validate();
    }

    public function toArray(): array
    {
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
