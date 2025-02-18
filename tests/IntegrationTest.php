<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests;

use SlackPhp\BlockKit\Surfaces\Modal;

class IntegrationTest extends TestCase
{
    public function testModal(): void
    {
        $modal = Modal::new()
            ->title('xxx_title')
            ->submit('xxx_submit')
            ->close('xxx_close')
            ->callbackId('xxx_callback_id')
            ->tap(static function (Modal $modal): void {
                $modal
                    ->newInput('xxx_text_input_block')
                    ->label('TextInput')
                    ->newTextInput('xxx_text_input_action')
                    ->maxLength(100)
                    ->minLength(10);
            })
            ->tap(static function (Modal $modal): void {
                $modal
                    ->newInput('xxx_number_input_block')
                    ->label('NumberInput')
                    ->newNumberInput('xxx_number_input_action')
                    ->setMaxValue(100)
                    ->setMinValue(10);
            })
            ->tap(static function (Modal $modal): void {
                $modal
                    ->newInput('xxx_rich_text_input_block')
                    ->label('RichTextInput')
                    ->newRichTextInput('xxx_rich_text_input_action')
                    ->placeholder('プレースホルダー')
                    ->initialValue('初期値')
                    ->focusOnLoad(true)
                    ->triggerActionOnCharacterEntered();
            });

        $this->assertJsonData([
            'title' => [
                'type' => 'plain_text',
                'text' => 'xxx_title',
            ],
            'submit' => [
                'type' => 'plain_text',
                'text' => 'xxx_submit',
            ],
            'close' => [
                'type' => 'plain_text',
                'text' => 'xxx_close',
            ],
            'callback_id' => 'xxx_callback_id',
            'type' => 'modal',
            'blocks' => [
                [
                    'type' => 'input',
                    'block_id' => 'xxx_text_input_block',
                    'label' => [
                        'type' => 'plain_text',
                        'text' => 'TextInput',
                    ],
                    'element' => [
                        'type' => 'plain_text_input',
                        'action_id' => 'xxx_text_input_action',
                        'min_length' => 10,
                        'max_length' => 100,
                    ],
                ],
                [
                    'type' => 'input',
                    'block_id' => 'xxx_number_input_block',
                    'label' => [
                        'type' => 'plain_text',
                        'text' => 'NumberInput',
                    ],
                    'element' => [
                        'type' => 'number_input',
                        'action_id' => 'xxx_number_input_action',
                        'min_value' => '10',
                        'max_value' => '100',
                        'is_decimal_allowed' => false,
                    ],
                ],
                [
                    'type' => 'input',
                    'block_id' => 'xxx_rich_text_input_block',
                    'label' => [
                        'type' => 'plain_text',
                        'text' => 'RichTextInput',
                    ],
                    'element' => [
                        'type' => 'rich_text_input',
                        'action_id' => 'xxx_rich_text_input_action',
                        'multiline' => true,
                        'placeholder' => [
                            'type' => 'plain_text',
                            'text' => 'プレースホルダー',
                        ],
                        'initial_value' => '初期値',
                        'focus_on_load' => true,
                        'dispatch_action_config' => [
                            'trigger_actions_on' => ['on_character_entered'],
                        ],
                    ],
                ],
            ],
        ], $modal->toArray());
    }
}
