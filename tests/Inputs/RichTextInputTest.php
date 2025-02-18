<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Inputs;

use SlackPhp\BlockKit\Inputs\RichTextInput;
use SlackPhp\BlockKit\Tests\TestCase;

/**
 * @covers \SlackPhp\BlockKit\Inputs\RichTextInput
 */
class RichTextInputTest extends TestCase
{
    public function testCanConfigureRichTextInput(): void
    {
        $input = (new RichTextInput())
            ->placeholder('テストプレースホルダー')
            ->initialValue('初期値テキスト')
            ->focusOnLoad(true)
            ->triggerActionOnCharacterEntered();

        $this->assertJsonData([
            'type' => 'rich_text_input',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'テストプレースホルダー',
            ],
            'initial_value' => '初期値テキスト',
            'focus_on_load' => true,
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

    public function testCanHydrateFromArray(): void
    {
        $data = [
            'type' => 'rich_text_input',
            'action_id' => 'rich_text_1',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'プレースホルダー',
            ],
            'initial_value' => '初期値',
            'focus_on_load' => true,
            'dispatch_action_config' => [
                'trigger_actions_on' => ['on_character_entered'],
            ],
        ];

        $input = RichTextInput::fromArray($data);

        $this->assertJsonData($data, $input);
    }
}
