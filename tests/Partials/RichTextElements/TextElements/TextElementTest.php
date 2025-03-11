<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\{Text, Link, User, Channel, Emoji, UserGroup, Date, Broadcast};
use SlackPhp\BlockKit\Tests\TestCase;
use SlackPhp\BlockKit\Type;

/**
 * @covers \SlackPhp\BlockKit\Partials\RichTextElements\TextElements\Link
 * @covers \SlackPhp\BlockKit\Partials\RichTextElements\TextElements\Text
 * @covers \SlackPhp\BlockKit\Partials\RichTextElements\TextElements\TextElement
 */
class TextElementTest extends TestCase
{
    public function testThatTextRendersToJsonCorrectly(): void
    {
        $text = new Text();
        $text->text('Hello, world!');

        $this->assertJsonData([
            'type' => Type::TEXT,
            'text' => 'Hello, world!',
        ], $text);
    }

    public function testThatTextWithStyleRendersToJsonCorrectly(): void
    {
        $text = new Text();
        $text->text('Hello, world!');
        $text->bold();
        $text->italic();

        $this->assertJsonData([
            'type' => Type::TEXT,
            'text' => 'Hello, world!',
            'style' => [
                'bold' => true,
                'italic' => true,
            ],
        ], $text);
    }

    public function testThatLinkRendersToJsonCorrectly(): void
    {
        $link = new Link();
        $link->url('https://example.com');
        $link->text('Example');

        $this->assertJsonData([
            'type' => Type::LINK,
            'url' => 'https://example.com',
            'text' => 'Example',
        ], $link);
    }

    public function testThatUserRendersToJsonCorrectly(): void
    {
        $user = new User();
        $user->userId('U12345');

        $this->assertJsonData([
            'type' => Type::USER,
            'user_id' => 'U12345',
        ], $user);
    }

    public function testThatChannelRendersToJsonCorrectly(): void
    {
        $channel = new Channel();
        $channel->channelId('C12345');

        $this->assertJsonData([
            'type' => Type::CHANNEL,
            'channel_id' => 'C12345',
        ], $channel);
    }

    public function testThatEmojiRendersToJsonCorrectly(): void
    {
        $emoji = new Emoji();
        $emoji->name('smile');

        $this->assertJsonData([
            'type' => Type::EMOJI,
            'name' => 'smile',
        ], $emoji);
    }

    public function testThatUserGroupRendersToJsonCorrectly(): void
    {
        $userGroup = new UserGroup();
        $userGroup->usergroupId('S12345');

        $this->assertJsonData([
            'type' => Type::USERGROUP,
            'usergroup_id' => 'S12345',
        ], $userGroup);
    }

    public function testThatDateRendersToJsonCorrectly(): void
    {
        $date = new Date();
        $date->timestamp('1234567890');
        $date->format('{date_long}');
        $date->link('https://example.com');

        $this->assertJsonData([
            'type' => Type::DATE,
            'timestamp' => '1234567890',
            'format' => '{date_long}',
            'link' => 'https://example.com',
        ], $date);
    }

    public function testThatBroadcastRendersToJsonCorrectly(): void
    {
        $broadcast = new Broadcast();
        $broadcast->channel();

        $this->assertJsonData([
            'type' => Type::BROADCAST,
            'range' => 'channel',
        ], $broadcast);
    }

    public function testThatTextValidatesText(): void
    {
        $text = new Text();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Text element must have a text value');

        $text->validate();
    }

    public function testThatLinkValidatesUrl(): void
    {
        $link = new Link();
        $link->text('Example');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Link element must have a url value');

        $link->validate();
    }

    public function testThatLinkValidatesText(): void
    {
        $link = new Link();
        $link->url('https://example.com');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Link element must have a text value');

        $link->validate();
    }
}
