<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\{Element, Exception, HydrationData, Type};
use SlackPhp\BlockKit\Partials\RichTextElements\{RichTextElement, RichTextSection, RichTextList, RichTextPreformatted, RichTextQuote};
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\{Text, TextElement};

class RichText extends BlockElement
{
    /**
     * @var RichTextElement[]
     */
    private array $elements = [];

    public function __construct(?string $blockId = null)
    {
        parent::__construct($blockId);
    }

    /**
     * 要素を追加する
     */
    public function addElement(RichTextElement $element): static
    {
        $this->elements[] = $element->setParent($this);

        return $this;
    }

    /**
     * 要素のコレクションを設定する
     *
     * @param RichTextElement[] $elements
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
     * @return RichTextElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * 新しいセクションを作成して追加する
     */
    public function newSection(): RichTextSection
    {
        $section = new RichTextSection();
        $this->addElement($section);

        return $section;
    }

    /**
     * テキストを含む新しいセクションを作成して追加する
     */
    public function addText(string $text, ?array $style = null): static
    {
        $section = $this->newSection();
        $textElement = new Text();
        $textElement->text($text);

        if ($style !== null) {
            $textElement->setStyle($style);
        }

        $section->addElement($textElement);

        return $this;
    }

    /**
     * 太字テキストを含む新しいセクションを作成して追加する
     */
    public function addBoldText(string $text): static
    {
        return $this->addText($text, ['bold' => true]);
    }

    /**
     * 斜体テキストを含む新しいセクションを作成して追加する
     */
    public function addItalicText(string $text): static
    {
        return $this->addText($text, ['italic' => true]);
    }

    /**
     * 取り消し線テキストを含む新しいセクションを作成して追加する
     */
    public function addStrikeText(string $text): static
    {
        return $this->addText($text, ['strike' => true]);
    }

    /**
     * 新しいリストを作成して追加する
     */
    public function newList(string $style = 'bullet'): RichTextList
    {
        $list = new RichTextList();
        $list->setStyle($style);
        $this->addElement($list);

        return $list;
    }

    /**
     * 新しい箇条書きリストを作成して追加する
     */
    public function newBulletList(): RichTextList
    {
        return $this->newList('bullet');
    }

    /**
     * 新しい番号付きリストを作成して追加する
     */
    public function newOrderedList(): RichTextList
    {
        return $this->newList('ordered');
    }

    /**
     * 新しいコードブロックを作成して追加する
     */
    public function addCode(string $code): static
    {
        $preformatted = new RichTextPreformatted();
        $preformatted->text($code);
        $this->addElement($preformatted);

        return $this;
    }

    /**
     * 新しい引用ブロックを作成して追加する
     */
    public function newQuote(): RichTextQuote
    {
        $quote = new RichTextQuote();
        $this->addElement($quote);

        return $quote;
    }

    /**
     * テキストを含む新しい引用ブロックを作成して追加する
     */
    public function addQuote(string $text): static
    {
        $quote = $this->newQuote();
        $textElement = new Text();
        $textElement->text($text);
        $quote->addElement($textElement);

        return $this;
    }

    /**
     * ブロックを検証する
     */
    public function validate(): void
    {
        if ($this->elements === []) {
            throw new Exception('RichText block must have at least one element');
        }

        foreach ($this->elements as $element) {
            $element->validate();
        }
    }

    /**
     * ブロックを配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['type'] = Type::RICH_TEXT;

        $elements = [];
        foreach ($this->elements as $element) {
            $elements[] = $element->toArray();
        }

        $data['elements'] = $elements;

        return $data;
    }

    /**
     * 配列からブロックを生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('elements')) {
            $elements = $data->useArray('elements');
            foreach ($elements as $element) {
                if (!isset($element['type'])) {
                    throw new Exception('RichText element data must include a type');
                }

                $type = $element['type'];
                $richTextElement = RichTextElement::createFromType($type);
                $richTextElement->hydrate(new HydrationData($element));
                $this->addElement($richTextElement);
            }
        }
    }
}
