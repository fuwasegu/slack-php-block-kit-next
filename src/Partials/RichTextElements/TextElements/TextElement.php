<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

abstract class TextElement extends Element
{
    /**
     * Get the element type
     */
    abstract public function getElementType(): string;

    /**
     * Convert the element to an array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['type'] = $this->getElementType();

        return $data;
    }

    /**
     * Create a TextElement from the specified type
     *
     * @param  string    $type Element type
     * @return static    Created element
     * @throws Exception
     */
    public static function createFromType(string $type): static
    {
        $class = self::getClassForType($type);

        if (!class_exists($class)) {
            throw new Exception('Unknown text element type: %s', [$type]);
        }

        // @phpstan-ignore-next-line
        return new $class();
    }

    /**
     * Get the class name corresponding to the type
     *
     * @param  string $type Element type
     * @return string Class name
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
            'color' => Color::class,
        ];

        return $map[$type] ?? '';
    }
}
