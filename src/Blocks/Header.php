<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\PlainText;

class Header extends BlockElement
{
    /**
     * @var PlainText
     */
    private $text;

    public function __construct(?string $blockId = null, ?string $text = null)
    {
        parent::__construct($blockId);

        if ($text !== null && $text !== '') {
            $this->text($text);
        }
    }

    public function setText(PlainText $text): static
    {
        $this->text = $text->setParent($this);

        return $this;
    }

    /**
     * @return static
     */
    public function text(string $text, ?bool $emoji = null)
    {
        return $this->setText(new PlainText($text, $emoji));
    }

    public function validate(): void
    {
        if (empty($this->text)) {
            throw new Exception('Header must contain "text"');
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['text'] = $this->text->toArray();

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('text')) {
            $this->setText(PlainText::fromArray($data->useElement('text')));
        }

        parent::hydrate($data);
    }
}
