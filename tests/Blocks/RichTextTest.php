<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Blocks;

use SlackPhp\BlockKit\Blocks\RichText;
use SlackPhp\BlockKit\Partials\RichTextElements\{RichTextSection, RichTextList, RichTextPreformatted, RichTextQuote};
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\{Text, Link};
use SlackPhp\BlockKit\Tests\TestCase;
use SlackPhp\BlockKit\Type;

/**
 * @covers \SlackPhp\BlockKit\Blocks\RichText
 */
class RichTextTest extends TestCase
{
    public function testThatRichTextRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id1');
        $richText->addText('Hello, world!');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id1',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'Hello, world!',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithStyledTextRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id2');
        $richText->addBoldText('Bold text');
        $richText->addItalicText('Italic text');
        $richText->addStrikeText('Strike text');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id2',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'Bold text',
                            'style' => [
                                'bold' => true,
                            ],
                        ],
                    ],
                ],
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'Italic text',
                            'style' => [
                                'italic' => true,
                            ],
                        ],
                    ],
                ],
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'Strike text',
                            'style' => [
                                'strike' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithListRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id3');
        $list = $richText->newBulletList();

        $section1 = new RichTextSection();
        $section1->addElement((new Text())->text('Item 1'));
        $list->addElement($section1);

        $section2 = new RichTextSection();
        $section2->addElement((new Text())->text('Item 2'));
        $list->addElement($section2);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id3',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_LIST,
                    'style' => 'bullet',
                    'elements' => [
                        [
                            'type' => Type::RICH_TEXT_SECTION,
                            'elements' => [
                                [
                                    'type' => Type::TEXT,
                                    'text' => 'Item 1',
                                ],
                            ],
                        ],
                        [
                            'type' => Type::RICH_TEXT_SECTION,
                            'elements' => [
                                [
                                    'type' => Type::TEXT,
                                    'text' => 'Item 2',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithCodeBlockRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id4');
        $richText->addCode('function hello() { return "Hello, world!"; }');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id4',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_PREFORMATTED,
                    'text' => 'function hello() { return "Hello, world!"; }',
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithQuoteRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id5');
        $richText->addQuote('This is a quote');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id5',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_QUOTE,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'This is a quote',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithComplexContentRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id6');
        $richText->addText('Hello, world!');

        $section = $richText->newSection();
        $section->addElement((new Text())->text('This is a '));
        $section->addElement((new Link())->url('https://example.com')->text('link'));

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id6',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'Hello, world!',
                        ],
                    ],
                ],
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'This is a ',
                        ],
                        [
                            'type' => Type::LINK,
                            'url' => 'https://example.com',
                            'text' => 'link',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }
}
