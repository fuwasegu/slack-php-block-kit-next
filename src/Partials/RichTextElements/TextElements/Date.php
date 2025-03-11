<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Date extends TextElement
{
    private ?string $timestamp = null;

    private ?string $format = null;

    private ?string $link = null;

    /**
     * タイムスタンプを設定する
     */
    public function timestamp(string $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * タイムスタンプを取得する
     */
    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    /**
     * フォーマットを設定する
     */
    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * フォーマットを取得する
     */
    public function getFormat(): ?string
    {
        return $this->format;
    }

    /**
     * リンクを設定する
     */
    public function link(string $link): static
    {
        $this->link = $link;

        return $this;
    }

    /**
     * リンクを取得する
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'date';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->timestamp === null || $this->timestamp === '') {
            throw new Exception('Date element must have a timestamp value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['timestamp'] = $this->timestamp;

        if ($this->format !== null) {
            $data['format'] = $this->format;
        }

        if ($this->link !== null) {
            $data['link'] = $this->link;
        }

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('timestamp')) {
            $this->timestamp($data->useValue('timestamp'));
        }

        if ($data->has('format')) {
            $this->format($data->useValue('format'));
        }

        if ($data->has('link')) {
            $this->link($data->useValue('link'));
        }
    }
}
