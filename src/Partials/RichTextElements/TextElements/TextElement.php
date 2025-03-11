<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

abstract class TextElement extends Element
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
     * 型から対応するTextElement要素を作成する
     *
     * @param  string    $type 要素の型
     * @throws Exception
     */
    public static function createFromType(string $type): self
    {
        $class = self::getClassForType($type);

        if (!class_exists($class)) {
            throw new Exception('Unknown text element type: %s', [$type]);
        }

        /** @var TextElement $element */
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
            'text' => Text::class,
            'channel' => Channel::class,
            'user' => User::class,
            'emoji' => Emoji::class,
            'link' => Link::class,
            'usergroup' => UserGroup::class,
            'date' => Date::class,
            'broadcast' => Broadcast::class,
        ];

        return $map[$type] ?? '';
    }
}
