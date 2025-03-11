<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Color extends TextElement
{
    private ?string $value = null;

    /**
     * カラー値を設定する
     */
    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * 色の値を取得する
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'color';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->value === null) {
            throw new Exception('Color element must have a value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['value'] = $this->value;

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('value')) {
            $this->setValue($data->useValue('value'));
        }
    }
}
