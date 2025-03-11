<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class Emoji extends TextElement
{
    private ?string $name = null;

    /**
     * 絵文字名を設定する
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * 絵文字名を取得する
     */
    public function getName(): ?string
    {
        return $this->name;
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
        if ($this->name === null || $this->name === '') {
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

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('name')) {
            $this->name($data->useValue('name'));
        }
    }
}
