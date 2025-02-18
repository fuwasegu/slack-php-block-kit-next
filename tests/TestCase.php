<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Tests;

use SlackPhp\BlockKit\Blocks\BlockElement;
use SlackPhp\BlockKit\Blocks\Virtual\VirtualBlock;
use SlackPhp\BlockKit\Surfaces\Surface;
use SlackPhp\BlockKit\Type;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use ReflectionException;
use ReflectionProperty;

class TestCase extends PhpUnitTestCase
{
    protected const TYPE_MOCK = 'mock';

    /**
     * @param object|array $expectedData
     * @param object|array $actualData
     */
    protected function assertJsonData($expectedData, $actualData): void
    {
        $expected = $this->jsonEncode($expectedData);
        $actual = $this->jsonEncode($actualData);
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    /**
     * @param object|array $data
     */
    protected function jsonEncode($data): string
    {
        $json = json_encode($data, JSON_THROW_ON_ERROR);
        if (!is_string($json)) {
            $this->fail('JSON encoding error in test.');
        }

        return $json;
    }

    protected function getMockSurface(): Surface
    {
        return new class () extends Surface {
            public function getType(): string
            {
                return Type::MESSAGE;
            }
        };
    }

    /**
     * @param BlockElement[] $subBlocks
     */
    protected function getMockVirtualBlock(array $subBlocks = []): VirtualBlock
    {
        $virtualBlock = new class () extends VirtualBlock {
            public function add(BlockElement $block): self
            {
                return $this->appendBlock($block);
            }

            public function getType(): string
            {
                return Type::SECTION;
            }
        };

        foreach ($subBlocks as $subBlock) {
            $virtualBlock->add($subBlock);
        }

        return $virtualBlock;
    }

    /**
     * @throws ReflectionException
     */
    protected function setStaticProperties(string $class, array $properties): void
    {
        foreach ($properties as $property => $value) {
            $reflection = new ReflectionProperty($class, $property);
            $reflection->setAccessible(true);
            $reflection->setValue(null, $value);
        }
    }
}
