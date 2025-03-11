<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Channel extends TextElement
{
    private ?string $channelId = null;

    /**
     * チャンネルIDを設定する
     */
    public function channelId(string $channelId): static
    {
        $this->channelId = $channelId;

        return $this;
    }

    /**
     * チャンネルIDを取得する
     */
    public function getChannelId(): ?string
    {
        return $this->channelId;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'channel';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->channelId === null || $this->channelId === '') {
            throw new Exception('Channel element must have a channel_id value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['channel_id'] = $this->channelId;

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('channel_id')) {
            $this->channelId($data->useValue('channel_id'));
        }
    }
}
