<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class RichTextList extends RichTextElement
{
    /**
     * @var RichTextSection[]
     */
    private array $elements = [];

    private ?string $style = null;

    private ?int $indent = null;

    private ?int $offset = null;

    private ?int $border = null;

    /**
     * 要素を追加する
     */
    public function addElement(RichTextSection $element): static
    {
        $this->elements[] = $element->setParent($this);

        return $this;
    }

    /**
     * 要素のコレクションを設定する
     *
     * @param RichTextSection[] $elements
     */
    public function setElements(array $elements): static
    {
        $this->elements = [];

        foreach ($elements as $element) {
            $this->addElement($element);
        }

        return $this;
    }

    /**
     * 要素のコレクションを取得する
     *
     * @return RichTextSection[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * リストスタイルを設定する
     */
    public function setStyle(string $style): static
    {
        if (!in_array($style, ['bullet', 'ordered'], true)) {
            throw new Exception('Invalid list style: %s', [$style]);
        }

        $this->style = $style;

        return $this;
    }

    /**
     * 箇条書きスタイルを設定する
     */
    public function bullet(): static
    {
        return $this->setStyle('bullet');
    }

    /**
     * 番号付きスタイルを設定する
     */
    public function ordered(): static
    {
        return $this->setStyle('ordered');
    }

    /**
     * インデントレベルを設定する
     */
    public function setIndent(int $indent): static
    {
        $this->indent = $indent;

        return $this;
    }

    /**
     * オフセットを設定する
     */
    public function setOffset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * ボーダーを設定する
     */
    public function setBorder(int $border): static
    {
        $this->border = $border;

        return $this;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'rich_text_list';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->style === null) {
            throw new Exception('RichTextList must have a style');
        }

        // elements は空配列も許可する（仕様に準拠）

        foreach ($this->elements as $element) {
            $element->validate();
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();

        $elements = [];
        foreach ($this->elements as $element) {
            $elements[] = $element->toArray();
        }

        $data['elements'] = $elements;
        $data['style'] = $this->style;

        if ($this->indent !== null) {
            $data['indent'] = $this->indent;
        }

        if ($this->offset !== null) {
            $data['offset'] = $this->offset;
        }

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

        if ($data->has('style')) {
            $this->setStyle($data->useValue('style'));
        } else {
            // style は必須属性なので、存在しない場合はエラーを投げる
            throw new Exception('RichTextList must have a style');
        }

        if ($data->has('indent')) {
            $this->setIndent($data->useValue('indent'));
        }

        if ($data->has('offset')) {
            $this->setOffset($data->useValue('offset'));
        }

        if ($data->has('border')) {
            $this->setBorder($data->useValue('border'));
        }

        if ($data->has('elements')) {
            $elements = $data->useArray('elements');
            foreach ($elements as $element) {
                if (!isset($element['type'])) {
                    throw new Exception('RichTextList element data must include a type');
                }

                if ($element['type'] !== 'rich_text_section') {
                    throw new Exception('RichTextList elements must be of type rich_text_section');
                }

                $section = new RichTextSection();
                $section->hydrate(new HydrationData($element));
                $this->addElement($section);
            }
        }
    }
}
