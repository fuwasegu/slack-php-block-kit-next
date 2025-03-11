<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Emoji extends TextElement
{
    private ?string $name = null;

    private ?string $unicode = null;

    /**
     * 絵文字名を設定する
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Unicode値を設定する
     */
    public function setUnicode(string $unicode): static
    {
        $this->unicode = $unicode;

        return $this;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'emoji';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->name === null) {
            throw new Exception('Emoji element must have a name value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['name'] = $this->name;

        if ($this->unicode !== null) {
            $data['unicode'] = $this->unicode;
        }

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('name')) {
            $this->setName($data->useValue('name'));
        }

        if ($data->has('unicode')) {
            $this->setUnicode($data->useValue('unicode'));
        }
    }
}
