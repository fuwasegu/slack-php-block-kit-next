<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Link extends TextElement
{
    private ?string $url = null;

    private ?string $text = null;

    /**
     * URLを設定する
     */
    public function url(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * URLを取得する
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * テキストを設定する
     */
    public function text(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    /**
     * テキストを取得する
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'link';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->url === null || $this->url === '') {
            throw new Exception('Link element must have a url value');
        }

        if ($this->text === null || $this->text === '') {
            throw new Exception('Link element must have a text value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['url'] = $this->url;
        $data['text'] = $this->text;

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('url')) {
            $this->url($data->useValue('url'));
        }

        if ($data->has('text')) {
            $this->text($data->useValue('text'));
        }
    }
}
