<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Broadcast extends TextElement
{
    private ?string $range = null;

    /**
     * 範囲を設定する
     */
    public function setRange(string $range): static
    {
        if (!in_array($range, ['here', 'channel', 'everyone'], true)) {
            throw new Exception('Broadcast range must be one of: here, channel, everyone');
        }

        $this->range = $range;

        return $this;
    }

    /**
     * 'here'範囲を設定する
     */
    public function here(): static
    {
        return $this->setRange('here');
    }

    /**
     * 'channel'範囲を設定する
     */
    public function channel(): static
    {
        return $this->setRange('channel');
    }

    /**
     * 'everyone'範囲を設定する
     */
    public function everyone(): static
    {
        return $this->setRange('everyone');
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'broadcast';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->range === null) {
            throw new Exception('Broadcast element must have a range value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['range'] = $this->range;

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('range')) {
            $this->setRange($data->useValue('range'));
        }
    }
}
