<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Blocks;

use SlackPhp\BlockKit\Blocks\RichText;
use SlackPhp\BlockKit\Tests\TestCase;
use SlackPhp\BlockKit\Type;

/**
 * @covers \SlackPhp\BlockKit\Blocks\RichText
 */
class RichTextFromArrayTest extends TestCase
{
    public function testFromArrayWithBasicText(): void
    {
        $data = [
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
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id1', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithStyledText(): void
    {
        $data = [
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
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id2', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithBulletList(): void
    {
        $data = [
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
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id3', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithOrderedList(): void
    {
        $data = [
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
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id3a', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithCodeBlock(): void
    {
        $data = [
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
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id4', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithQuote(): void
    {
        $data = [
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
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id5', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithBroadcast(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id6',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'broadcast',
                            'range' => 'here',
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id6', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithColor(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id7',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'color',
                            'value' => '#FF0000',
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id7', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithChannel(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id8',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'channel',
                            'channel_id' => 'C12345678',
                            'style' => [
                                'bold' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id8', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithDate(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id9',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'date',
                            'timestamp' => 1_234_567_890,
                            'format' => '{date_long}',
                            'url' => 'https://example.com',
                            'fallback' => 'February 13, 2009',
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id9', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithEmoji(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id10',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'emoji',
                            'name' => 'smile',
                            'unicode' => '1F604',
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id10', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithLink(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id11',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'link',
                            'url' => 'https://example.com',
                            'text' => 'Example Link',
                            'style' => [
                                'bold' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id11', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithUser(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id12',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'user',
                            'user_id' => 'U12345678',
                            'style' => [
                                'bold' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id12', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }

    public function testFromArrayWithUserGroup(): void
    {
        $data = [
            'type' => Type::RICH_TEXT,
            'block_id' => 'id13',
            'elements' => [
                [
                    'type' => Type::RICH_TEXT_SECTION,
                    'elements' => [
                        [
                            'type' => 'usergroup',
                            'usergroup_id' => 'S12345678',
                            'style' => [
                                'bold' => true,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $richText = RichText::fromArray($data);

        $this->assertSame('id13', $richText->getBlockId());
        $this->assertJsonData($data, $richText);
    }
}
