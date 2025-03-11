<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Date extends TextElement
{
    private ?int $timestamp = null;

    private ?string $format = null;

    private ?string $url = null;

    private ?string $fallback = null;

    /**
     * タイムスタンプを設定する
     */
    public function setTimestamp(int $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * フォーマットを設定する
     */
    public function setFormat(string $format): static
    {
        if ($format === '') {
            throw new Exception('Date format cannot be empty');
        }

        $this->format = $format;

        return $this;
    }

    /**
     * URLを設定する
     */
    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * フォールバックテキストを設定する
     */
    public function setFallback(string $fallback): static
    {
        $this->fallback = $fallback;

        return $this;
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
        if ($this->timestamp === null) {
            throw new Exception('Date element must have a timestamp value');
        }

        if ($this->format === null) {
            throw new Exception('Date element must have a format value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['timestamp'] = $this->timestamp;
        $data['format'] = $this->format;

        if ($this->url !== null) {
            $data['url'] = $this->url;
        }

        if ($this->fallback !== null) {
            $data['fallback'] = $this->fallback;
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
            $this->setTimestamp($data->useValue('timestamp'));
        }

        if ($data->has('format')) {
            $this->setFormat($data->useValue('format'));
        }

        if ($data->has('url')) {
            $this->setUrl($data->useValue('url'));
        }

        if ($data->has('fallback')) {
            $this->setFallback($data->useValue('fallback'));
        }
    }
}
