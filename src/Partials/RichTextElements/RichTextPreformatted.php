<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class RichTextPreformatted extends RichTextElement
{
    private ?string $text = null;

    private ?string $border = null;

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
     * ボーダーを設定する
     */
    public function setBorder(string $border): static
    {
        $this->border = $border;

        return $this;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'rich_text_preformatted';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->text === null || $this->text === '') {
            throw new Exception('RichTextPreformatted must have a text value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['text'] = $this->text;

        if ($this->border !== null) {
            $data['border'] = $this->border;
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

        if ($data->has('border')) {
            $this->setBorder($data->useValue('border'));
        }
    }
}
