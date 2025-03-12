<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

abstract class RichTextElement extends Element
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
     * Create a RichTextElement from the specified type
     *
     * @param  string    $type Element type
     * @return static    Created element
     * @throws Exception
     */
    public static function createFromType(string $type): static
    {
        $class = self::getClassForType($type);

        if (!class_exists($class)) {
            throw new Exception('Unknown rich text element type: %s', [$type]);
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
            'rich_text_section' => RichTextSection::class,
            'rich_text_list' => RichTextList::class,
            'rich_text_preformatted' => RichTextPreformatted::class,
            'rich_text_quote' => RichTextQuote::class,
        ];

        return $map[$type] ?? '';
    }
}
