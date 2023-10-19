<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Surfaces;

use SlackPhp\BlockKit\{
    Exception,
    HydrationData,
    Partials\PlainText,
    Type,
};

/**
 * Modals provide focused spaces ideal for requesting and collecting data from users, or temporarily displaying dynamic
 * and interactive information.
 *
 * @see https://api.slack.com/surfaces
 */
class Modal extends View
{
    private const MAX_LENGTH_TITLE = 24;

    private ?PlainText $title = null;

    private ?PlainText $submit = null;

    private ?PlainText $close = null;

    private bool $clearOnClose = false;

    private bool $notifyOnClose = false;

    public function setTitle(PlainText $title): static
    {
        $this->title = $title->setParent($this);

        return $this;
    }

    public function setSubmit(PlainText $title): static
    {
        $this->submit = $title->setParent($this);

        return $this;
    }

    public function setClose(PlainText $title): static
    {
        $this->close = $title->setParent($this);

        return $this;
    }

    public function title(string $title): static
    {
        return $this->setTitle(new PlainText($title));
    }

    public function submit(string $submit): static
    {
        return $this->setSubmit(new PlainText($submit));
    }

    public function close(string $close): static
    {
        return $this->setClose(new PlainText($close));
    }

    public function clearOnClose(bool $clearOnClose): static
    {
        $this->clearOnClose = $clearOnClose;

        return $this;
    }

    public function notifyOnClose(bool $notifyOnClose): static
    {
        $this->notifyOnClose = $notifyOnClose;

        return $this;
    }

    public function validate(): void
    {
        parent::validate();

        if ($this->title === null) {
            throw new Exception('Modals must have a title');
        }
        $this->title->validateWithLength(self::MAX_LENGTH_TITLE);

        $hasInputs = false;
        foreach ($this->getBlocks() as $block) {
            if ($block->getType() === Type::INPUT) {
                $hasInputs = true;

                break;
            }
        }
        if ($hasInputs && !$this->submit instanceof PlainText) {
            throw new Exception('Modals must have a "submit" button defined if they contain any "input" blocks');
        }
    }

    public function toArray(): array
    {
        $data = [];

        assert($this->title !== null);

        $data['title'] = $this->title->toArray();

        if ($this->submit instanceof PlainText) {
            $data['submit'] = $this->submit->toArray();
        }

        if ($this->close instanceof PlainText) {
            $data['close'] = $this->close->toArray();
        }

        if ($this->clearOnClose === true) {
            $data['clear_on_close'] = $this->clearOnClose;
        }

        if ($this->notifyOnClose === true) {
            $data['notify_on_close'] = $this->notifyOnClose;
        }

        return $data + parent::toArray();
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('title')) {
            $this->setTitle(PlainText::fromArray($data->useElement('title')));
        }

        if ($data->has('submit')) {
            $this->setSubmit(PlainText::fromArray($data->useElement('submit')));
        }

        if ($data->has('close')) {
            $this->setClose(PlainText::fromArray($data->useElement('close')));
        }

        if ($data->has('clear_on_close')) {
            $this->clearOnClose($data->useValue('clear_on_close'));
        }

        if ($data->has('notify_on_close')) {
            $this->notifyOnClose($data->useValue('notify_on_close'));
        }

        parent::hydrate($data);
    }
}
