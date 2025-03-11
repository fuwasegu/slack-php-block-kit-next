<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\TextElement;

class RichTextQuote extends RichTextElement
{
    /**
     * @var TextElement[]
     */
    private array $elements = [];

    private ?int $border = null;

    /**
     * 要素を追加する
     */
    public function addElement(TextElement $element): static
    {
        $this->elements[] = $element->setParent($this);

        return $this;
    }

    /**
     * 要素のコレクションを設定する
     *
     * @param TextElement[] $elements
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
     * @return TextElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
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
        return 'rich_text_quote';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
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

        if ($data->has('border')) {
            $this->setBorder($data->useValue('border'));
        }

        if ($data->has('elements')) {
            $elements = $data->useArray('elements');
            foreach ($elements as $element) {
                if (!isset($element['type'])) {
                    throw new Exception('RichTextQuote element data must include a type');
                }

                $type = $element['type'];
                $textElement = TextElement::createFromType($type);
                $textElement->hydrate(new HydrationData($element));
                $this->addElement($textElement);
            }
        }
    }
}
