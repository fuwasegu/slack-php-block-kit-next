<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

abstract class RichTextElement extends Element
{
    /**
     * 要素の型を取得する
     */
    abstract public function getElementType(): string;

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['type'] = $this->getElementType();

        return $data;
    }

    /**
     * 型から対応するRichTextElement要素を作成する
     *
     * @param  string    $type 要素の型
     * @throws Exception
     */
    public static function createFromType(string $type): self
    {
        $class = self::getClassForType($type);

        if (!class_exists($class)) {
            throw new Exception('Unknown rich text element type: %s', [$type]);
        }

        /** @var RichTextElement $element */
        return new $class();
    }

    /**
     * 型に対応するクラス名を取得する
     *
     * @param  string $type 要素の型
     * @return string クラス名
     */
    protected static function getClassForType(string $type): string
    {
        $map = [
            'rich_text_section' => RichTextSection::class,
            'rich_text_list' => RichTextList::class,
            'rich_text_preformatted' => RichTextPreformatted::class,
            'rich_text_quote' => RichTextQuote::class,
        ];

        return $map[$type] ?? '';
    }
}
