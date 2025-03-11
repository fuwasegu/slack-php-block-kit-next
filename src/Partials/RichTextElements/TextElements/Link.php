<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Link extends TextElement
{
    private ?string $url = null;

    private ?string $text = null;

    private ?bool $unsafe = null;

    private ?array $style = null;

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
     * 安全でないリンクかどうかを設定する
     */
    public function setUnsafe(bool $unsafe): static
    {
        $this->unsafe = $unsafe;

        return $this;
    }

    /**
     * スタイルを設定する
     */
    public function setStyle(array $style): static
    {
        // スタイル属性がブール値であることを確認
        foreach ($style as $key => $value) {
            if (!in_array($key, ['bold', 'italic', 'strike', 'code'], true)) {
                throw new Exception('Invalid style property for Link element: %s', [$key]);
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
        // text は仕様では任意なので、検証しない

        // スタイルが設定されている場合は検証
        if ($this->style !== null) {
            foreach ($this->style as $key => $value) {
                if (!in_array($key, ['bold', 'italic', 'strike', 'code'], true)) {
                    throw new Exception('Invalid style property for Link element: %s', [$key]);
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
        $data['url'] = $this->url;

        // text は任意なので、設定されている場合のみ含める
        if ($this->text !== null) {
            $data['text'] = $this->text;
        }

        if ($this->unsafe !== null) {
            $data['unsafe'] = $this->unsafe;
        }

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

        if ($data->has('url')) {
            $this->url($data->useValue('url'));
        }

        if ($data->has('text')) {
            $this->text($data->useValue('text'));
        }

        if ($data->has('unsafe')) {
            $this->setUnsafe($data->useValue('unsafe'));
        }

        if ($data->has('style')) {
            $this->setStyle($data->useArray('style'));
        }
    }
}
