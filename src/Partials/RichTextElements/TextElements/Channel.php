<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Channel extends TextElement
{
    private ?string $channelId = null;

    private ?array $style = null;

    /**
     * チャンネルIDを設定する
     */
    public function setChannelId(string $channelId): static
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
     * スタイルを設定する
     */
    public function setStyle(array $style): static
    {
        // スタイル属性がブール値であることを確認
        foreach ($style as $key => $value) {
            if (!in_array($key, ['bold', 'italic', 'strike', 'highlight', 'client_highlight', 'unlink'], true)) {
                throw new Exception('Invalid style property for Channel element: %s', [$key]);
            }

            if (!is_bool($value)) {
                throw new Exception('Style property must be a boolean value: %s', [$key]);
            }
        }

        $this->style = $style;

        return $this;
    }

    /**
     * 太字スタイルを設定する
     */
    public function bold(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['bold'] = $flag;

        return $this;
    }

    /**
     * 斜体スタイルを設定する
     */
    public function italic(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['italic'] = $flag;

        return $this;
    }

    /**
     * 取り消し線スタイルを設定する
     */
    public function strike(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['strike'] = $flag;

        return $this;
    }

    /**
     * ハイライトスタイルを設定する
     */
    public function highlight(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['highlight'] = $flag;

        return $this;
    }

    /**
     * クライアントハイライトスタイルを設定する
     */
    public function clientHighlight(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['client_highlight'] = $flag;

        return $this;
    }

    /**
     * リンク解除スタイルを設定する
     */
    public function unlink(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['unlink'] = $flag;

        return $this;
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
        if ($this->channelId === null) {
            throw new Exception('Channel element must have a channel_id value');
        }

        // スタイルが設定されている場合は検証
        if ($this->style !== null) {
            foreach ($this->style as $key => $value) {
                if (!in_array($key, ['bold', 'italic', 'strike', 'highlight', 'client_highlight', 'unlink'], true)) {
                    throw new Exception('Invalid style property for Channel element: %s', [$key]);
                }

                if (!is_bool($value)) {
                    throw new Exception('Style property must be a boolean value: %s', [$key]);
                }
            }
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['channel_id'] = $this->channelId;

        if ($this->style !== null && $this->style !== []) {
            $data['style'] = $this->style;
        }

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('channel_id')) {
            $this->setChannelId($data->useValue('channel_id'));
        }

        if ($data->has('style')) {
            $this->setStyle($data->useArray('style'));
        }
    }
}
