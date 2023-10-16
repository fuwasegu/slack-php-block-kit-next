<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests\Inputs;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\Inputs\NumberInput;
use SlackPhp\BlockKit\Tests\TestCase;

/**
 * @covers \SlackPhp\BlockKit\Inputs\NumberInput
 */
class NumberInputTest extends TestCase
{
    public function test(): void
    {
        $input = (new NumberInput('number-input-action'))
            ->placeholder('foo')
            ->setIsDecimalAllowed(true)
            ->setInitialValue(10)
            ->setMinValue(1)
            ->setMaxValue(20)
            ->setFocusOnLoad(true);

        $this->assertJsonData([
            'type' => 'number_input',
            'action_id' => 'number-input-action',
            'placeholder' => [
                'type' => 'plain_text',
                'text' => 'foo',
            ],
            'is_decimal_allowed' => true,
            'initial_value' => 10,
            'min_value' => 1,
            'max_value' => 20,
            'focus_on_load' => true,
        ], $input);
    }

    public function testMinValueIsGreaterThanMaxValue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Number input max value must be greater than min value');
        $input = (new NumberInput())->setMaxValue(1)->setMinValue(2);

        $input->validate();
    }

    public function testSettingNonIntInitialValueWhenIsDecimalAllowedIsTrue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The initial value must be only decimal number when is_decimal_allowed is true');
        $input = (new NumberInput())->setIsDecimalAllowed(true)->setInitialValue(1.2);

        $input->validate();
    }

    public function testInitialValueIsGreaterThanMaxValue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The initial value must be less than or equal to max_value');
        $input = (new NumberInput())->setMaxValue(1)->setInitialValue(2);

        $input->validate();
    }

    public function testInitialValueIsSmallerThanMinValue(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('The initial value must be greater than or equal to min_value');
        $input = (new NumberInput())->setMinValue(2)->setInitialValue(1);

        $input->validate();
    }
}
