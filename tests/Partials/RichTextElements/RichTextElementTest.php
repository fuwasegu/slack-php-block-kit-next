<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Partials\RichTextElements;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\Partials\RichTextElements\{RichTextSection, RichTextList};
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\Text;
use SlackPhp\BlockKit\Tests\TestCase;
use SlackPhp\BlockKit\Type;

/**
 * @covers \SlackPhp\BlockKit\Partials\RichTextElements\RichTextElement
 * @covers \SlackPhp\BlockKit\Partials\RichTextElements\RichTextSection
 */
class RichTextElementTest extends TestCase
{
    public function testThatRichTextSectionRendersToJsonCorrectly(): void
    {
        $section = new RichTextSection();
        $section->addElement((new Text())->text('Hello, world!'));

        $this->assertJsonData([
            'type' => Type::RICH_TEXT_SECTION,
            'elements' => [
                [
                    'type' => Type::TEXT,
                    'text' => 'Hello, world!',
                ],
            ],
        ], $section);
    }

    public function testThatRichTextSectionWithMultipleElementsRendersToJsonCorrectly(): void
    {
        $section = new RichTextSection();
        $section->addElement((new Text())->text('Hello, '));
        $section->addElement((new Text())->text('world!')->bold());

        $this->assertJsonData([
            'type' => Type::RICH_TEXT_SECTION,
            'elements' => [
                [
                    'type' => Type::TEXT,
                    'text' => 'Hello, ',
                ],
                [
                    'type' => Type::TEXT,
                    'text' => 'world!',
                    'style' => [
                        'bold' => true,
                    ],
                ],
            ],
        ], $section);
    }

    public function testThatRichTextListRendersToJsonCorrectly(): void
    {
        $list = new RichTextList();
        $list->bullet();

        $section1 = new RichTextSection();
        $section1->addElement((new Text())->text('Item 1'));
        $list->addElement($section1);

        $section2 = new RichTextSection();
        $section2->addElement((new Text())->text('Item 2'));
        $list->addElement($section2);

        $this->assertJsonData([
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
        ], $list);
    }

    public function testThatRichTextListWithIndentAndBorderRendersToJsonCorrectly(): void
    {
        $list = new RichTextList();
        $list->ordered();
        $list->setIndent(1);
        $list->setBorder(1);

        $section = new RichTextSection();
        $section->addElement((new Text())->text('Item 1'));
        $list->addElement($section);

        $this->assertJsonData([
            'type' => Type::RICH_TEXT_LIST,
            'style' => 'ordered',
            'indent' => 1,
            'border' => 1,
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
            ],
        ], $list);
    }

    public function testThatRichTextListValidatesStyle(): void
    {
        $list = new RichTextList();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('RichTextList must have a style');

        $list->validate();
    }

    public function testThatRichTextListValidatesElements(): void
    {
        $list = new RichTextList();
        // An exception should be thrown when calling validate without setting a style

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('RichTextList must have a style');

        $list->validate();
    }
}
