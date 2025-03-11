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

    public function testThatRichTextWithListWithOptionsRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id3a');
        $list = $richText->newOrderedList(10, 5, 2);

        $section1 = new RichTextSection();
        $section1->addElement((new Text())->text('Item 1'));
        $list->addElement($section1);

        $section2 = new RichTextSection();
        $section2->addElement((new Text())->text('Item 2'));
        $list->addElement($section2);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id3a',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_LIST,
                    'style' => 'ordered',
                    'indent' => 10,
                    'offset' => 5,
                    'border' => 2,
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
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'function hello() { return "Hello, world!"; }',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithCodeBlockWithBorderRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id4a');
        $richText->addCode('function hello() { return "Hello, world!"; }', 3);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id4a',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_PREFORMATTED,
                    'border' => 3,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'function hello() { return "Hello, world!"; }',
                        ],
                    ],
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

    public function testThatRichTextWithQuoteWithBorderRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id5a');
        $richText->addQuote('This is a quote with border', 2);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id5a',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_QUOTE,
                    'border' => 2,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'This is a quote with border',
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

    public function testThatRichTextWithBroadcastRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id7');
        $richText->addBroadcast('channel');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id7',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::BROADCAST,
                            'range' => 'channel',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithColorRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id8');
        $richText->addColor('#FF5733');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id8',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::COLOR,
                            'value' => '#FF5733',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithColorNameRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id8a');
        $richText->addColor('red');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id8a',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::COLOR,
                            'value' => 'red',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithChannelRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id9');
        $richText->addChannel('C12345678', ['bold' => true, 'italic' => true]);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id9',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::CHANNEL,
                            'channel_id' => 'C12345678',
                            'style' => [
                                'bold' => true,
                                'italic' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithDateRendersToJsonCorrectly(): void
    {
        $timestamp = 1_609_459_200; // 2021-01-01 00:00:00 UTC
        $richText = new RichText('id10');
        $richText->addDate($timestamp, '{date_long}', 'https://example.com', 'January 1, 2021');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id10',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::DATE,
                            'timestamp' => $timestamp,
                            'format' => '{date_long}',
                            'url' => 'https://example.com',
                            'fallback' => 'January 1, 2021',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithEmojiRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id11');
        $richText->addEmoji('wave', '👋');

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id11',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::EMOJI,
                            'name' => 'wave',
                            'unicode' => '👋',
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithUserRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id12');
        $richText->addUser('U12345678', ['bold' => true, 'highlight' => true]);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id12',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::USER,
                            'user_id' => 'U12345678',
                            'style' => [
                                'bold' => true,
                                'highlight' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testThatRichTextWithUserGroupRendersToJsonCorrectly(): void
    {
        $richText = new RichText('id13');
        $richText->addUserGroup('S12345678', ['italic' => true, 'strike' => true]);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'block_id' => 'id13',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::USERGROUP,
                            'usergroup_id' => 'S12345678',
                            'style' => [
                                'italic' => true,
                                'strike' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ], $richText);
    }

    public function testRichTextInputInitialValue(): void
    {
        $richText = new RichText();

        $section = new RichTextSection();
        $section->addElement((new Text())->text('テストテキスト'));
        $richText->addElement($section);

        $list = new RichTextList();
        $list->setStyle('bullet');

        $listItem1 = new RichTextSection();
        $listItem1->addElement((new Text())->text('リストアイテム1'));
        $list->addElement($listItem1);

        $listItem2 = new RichTextSection();
        $listItem2->addElement((new Text())->text('リストアイテム2'));
        $list->addElement($listItem2);

        $richText->addElement($list);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT,
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => Type::TEXT,
                            'text' => 'テストテキスト',
                        ],
                    ],
                ],
                [
                    'type' => Type::RICH_TEXT_LIST,
                    'style' => 'bullet',
                    'elements' => [
                        [
                            'type' => Type::RICH_TEXT_SECTION,
                            'elements' => [
                                [
                                    'type' => Type::TEXT,
                                    'text' => 'リストアイテム1',
                                ],
                            ],
                        ],
                        [
                            'type' => Type::RICH_TEXT_SECTION,
                            'elements' => [
                                [
                                    'type' => Type::TEXT,
                                    'text' => 'リストアイテム2',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ], $richText);
    }
}
