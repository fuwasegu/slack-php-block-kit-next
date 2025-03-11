<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Inputs;

use SlackPhp\BlockKit\Blocks\RichText;
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

        $richText = new RichText();
        $richText->addElement($section);

        $input = (new RichTextInput())
            ->placeholder('テストプレースホルダー')
            ->focusOnLoad(true)
            ->initialValue($richText)
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

        $richText = new RichText();
        $richText->addElement($section);

        $input = (new RichTextInput())
            ->initialValue($richText);

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
        $section = new RichTextSection();
        $section->addElement((new Text())->text('初期テキスト値'));

        $richText = new RichText();
        $richText->addElement($section);

        $input = new RichTextInput();
        $input->actionId('rich_text_1')
            ->placeholder('プレースホルダー')
            ->focusOnLoad(true)
            ->initialValue($richText)
            ->triggerActionOnCharacterEntered();

        $this->assertJsonData([
            'type' => 'rich_text_input',
            'action_id' => 'rich_text_1',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'プレースホルダー',
            ],
            'focus_on_load' => true,
            'initial_value' => [
                [
                    'type' => 'rich_text_section',
                    'elements' => [
                        [
                            'type' => 'text',
                            'text' => '初期テキスト値',
                        ],
                    ],
                ],
            ],
            'dispatch_action_config' => [
                'trigger_actions_on' => ['on_character_entered'],
            ],
        ], $input);
    }
}
