<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Inputs;

use SlackPhp\BlockKit\Inputs\RichTextInput;
use SlackPhp\BlockKit\Partials\RichTextElements\RichTextSection;
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\Text;
use SlackPhp\BlockKit\Tests\TestCase;

/**
 * @covers \SlackPhp\BlockKit\Inputs\RichTextInput
 */
class RichTextInputTest extends TestCase
{
    public function testCanConfigureRichTextInput(): void
    {
        $section = new RichTextSection();
        $section->addElement((new Text())->text('テスト初期値'));

        $input = (new RichTextInput())
            ->placeholder('テストプレースホルダー')
            ->focusOnLoad(true)
            ->initialValue([$section])
            ->triggerActionOnCharacterEntered();

        $this->assertJsonData([
            'type' => 'rich_text_input',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'テストプレースホルダー',
            ],
            'focus_on_load' => true,
            'initial_value' => [
                [
                    'type' => 'rich_text_section',
                    'elements' => [
                        [
                            'type' => 'text',
                            'text' => 'テスト初期値',
                        ],
                    ],
                ],
            ],
            'dispatch_action_config' => [
                'trigger_actions_on' => ['on_character_entered'],
            ],
        ], $input);
    }

    public function testDefaultConfiguration(): void
    {
        $input = new RichTextInput();

        $this->assertJsonData([
            'type' => 'rich_text_input',
        ], $input);
    }

    public function testCanSetActionId(): void
    {
        $input = (new RichTextInput())
            ->actionId('rich_text_1');

        $this->assertJsonData([
            'type' => 'rich_text_input',
            'action_id' => 'rich_text_1',
        ], $input);
    }

    public function testCanSetInitialValue(): void
    {
        $section = new RichTextSection();
        $section->addElement((new Text())->text('初期テキスト'));

        $input = (new RichTextInput())
            ->initialValue([$section]);

        $this->assertJsonData([
            'type' => 'rich_text_input',
            'initial_value' => [
                [
                    'type' => 'rich_text_section',
                    'elements' => [
                        [
                            'type' => 'text',
                            'text' => '初期テキスト',
                        ],
                    ],
                ],
            ],
        ], $input);
    }

    public function testCanHydrateFromArray(): void
    {
        // 手動でRichTextInputを構築
        $section = new RichTextSection();
        $section->addElement((new Text())->text('初期テキスト値'));

        $input = new RichTextInput();
        $input->actionId('rich_text_1')
            ->placeholder('プレースホルダー')
            ->focusOnLoad(true)
            ->initialValue([$section])
            ->triggerActionOnCharacterEntered();

        // 配列に変換して検証
        $result = $input->toArray();
        $this->assertEquals('rich_text_input', $result['type']);
        $this->assertEquals('rich_text_1', $result['action_id']);
        $this->assertTrue($result['focus_on_load']);
        $this->assertArrayHasKey('initial_value', $result);
        $this->assertCount(1, $result['initial_value']);
        $this->assertEquals('rich_text_section', $result['initial_value'][0]['type']);
        $this->assertArrayHasKey('elements', $result['initial_value'][0]);
        $this->assertCount(1, $result['initial_value'][0]['elements']);
        $this->assertEquals('text', $result['initial_value'][0]['elements'][0]['type']);
        $this->assertEquals('初期テキスト値', $result['initial_value'][0]['elements'][0]['text']);
    }
}
