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
        if ($indent < 0) {
            throw new Exception('Indent must be a non-negative integer');
        }

        $this->indent = $indent;

        return $this;
    }

    /**
     * ボーダーを設定する
     */
    public function setBorder(int $border): static
    {
        if ($border < 0) {
            throw new Exception('Border must be a non-negative integer');
        }

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

        if ($this->elements === []) {
            throw new Exception('RichTextList must have at least one element');
        }

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
        }

        if ($data->has('indent')) {
            $this->setIndent($data->useValue('indent'));
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
