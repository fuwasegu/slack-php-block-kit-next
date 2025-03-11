<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Text extends TextElement
{
    private ?string $text = null;

    private ?array $style = null;

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
     * スタイルを設定する
     */
    public function setStyle(array $style): static
    {
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
     * コードスタイルを設定する
     */
    public function code(bool $flag = true): static
    {
        $this->style ??= [];
        $this->style['code'] = $flag;

        return $this;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'text';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->text === null || $this->text === '') {
            throw new Exception('Text element must have a text value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['text'] = $this->text;

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

        if ($data->has('text')) {
            $this->text($data->useValue('text'));
        }

        if ($data->has('style')) {
            $this->setStyle($data->useArray('style'));
        }
    }
}
