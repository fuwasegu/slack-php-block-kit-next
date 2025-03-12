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
        $section->addElement((new Text())->text('Test initial value'));

        $richText = new RichText();
        $richText->addElement($section);

        $input = (new RichTextInput())
            ->placeholder('Test placeholder')
            ->focusOnLoad(true)
            ->initialValue($richText)
            ->triggerActionOnCharacterEntered();

        $this->assertJsonData([
            'type' => 'rich_text_input',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'Test placeholder',
            ],
            'focus_on_load' => true,
            'initial_value' => [
                [
                    'type' => 'rich_text_section',
                    'elements' => [
                        [
                            'type' => 'text',
                            'text' => 'Test initial value',
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
        $section->addElement((new Text())->text('Initial text'));

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
                            'text' => 'Initial text',
                        ],
                    ],
                ],
            ],
        ], $input);
    }

    public function testCanHydrateFromArray(): void
    {
        $section = new RichTextSection();
        $section->addElement((new Text())->text('Initial text value'));

        $richText = new RichText();
        $richText->addElement($section);

        $input = new RichTextInput();
        $input->actionId('rich_text_1')
            ->placeholder('Placeholder')
            ->focusOnLoad(true)
            ->initialValue($richText)
            ->triggerActionOnCharacterEntered();

        $this->assertJsonData([
            'type' => 'rich_text_input',
            'action_id' => 'rich_text_1',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'Placeholder',
            ],
            'focus_on_load' => true,
            'initial_value' => [
                [
                    'type' => 'rich_text_section',
                    'elements' => [
                        [
                            'type' => 'text',
                            'text' => 'Initial text value',
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
