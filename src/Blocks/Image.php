<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\{Exception, HydrationData, Surfaces\Surface};
use SlackPhp\BlockKit\Partials\PlainText;

class Image extends BlockElement
{
    /**
     * @var PlainText
     */
    private $title;

    private ?string $url = null;

    private ?string $altText = null;

    public function __construct(?string $blockId = null, ?string $url = null, ?string $altText = null)
    {
        parent::__construct($blockId);

        if ($url !== null && $url !== '') {
            $this->url($url);
        }

        if ($altText !== null && $altText !== '') {
            $this->altText($altText);
        }
    }

    public function setTitle(PlainText $title): static
    {
        $this->title = $title->setParent($this);

        return $this;
    }

    /**
     * @return static
     */
    public function title(string $text)
    {
        return $this->setTitle(new PlainText($text));
    }

    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function altText(string $alt): static
    {
        $this->altText = $alt;

        return $this;
    }

    public function validate(): void
    {
        if (empty($this->url)) {
            throw new Exception('Image must contain "image_url"');
        }

        if (empty($this->altText)) {
            throw new Exception('Image must contain "alt_text"');
        }

        if (!empty($this->title)) {
            $this->title->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $isBlock = !$this->getParent() instanceof \SlackPhp\BlockKit\Element || $this->getParent() instanceof Surface;

        if ($isBlock && !empty($this->title)) {
            $data['title'] = $this->title->toArray();
        }

        if (!$isBlock) {
            unset($data['block_id']);
        }

        $data['image_url'] = $this->url;
        $data['alt_text'] = $this->altText;

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('title')) {
            $this->setTitle(PlainText::fromArray($data->useElement('title')));
        }

        if ($data->has('image_url')) {
            $this->url($data->useValue('image_url'));
        }

        if ($data->has('alt_text')) {
            $this->altText($data->useValue('alt_text'));
        }

        parent::hydrate($data);
    }
}
