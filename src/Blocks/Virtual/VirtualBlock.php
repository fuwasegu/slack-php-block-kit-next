<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks\Virtual;

use Iterator;
use IteratorAggregate;
use SlackPhp\BlockKit\Blocks\BlockElement;
use SlackPhp\BlockKit\Element;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\HydrationException;

/**
 * An encapsulation of multiple blocks acting as one virtual element.
 *
 * @implements IteratorAggregate<BlockElement>
 */
abstract class VirtualBlock extends BlockElement implements IteratorAggregate
{
    private int $index = 1;

    /**
     * @var BlockElement[]
     */
    private array $blocks = [];

    protected function appendBlock(BlockElement $block): static
    {
        if ($this->getParent() instanceof Element) {
            $block->setParent($this->getParent());
        }

        $this->blocks[] = $this->assignBlockId($block);

        return $this;
    }

    protected function prependBlock(BlockElement $block): static
    {
        if ($this->getParent() instanceof Element) {
            $block->setParent($this->getParent());
        }

        $this->blocks = array_merge(
            [$this->assignBlockId($block, 1)],
            array_map(fn (BlockElement $block): BlockElement => $this->assignBlockId($block), $this->blocks),
        );

        return $this;
    }

    private function assignBlockId(BlockElement $block, ?int $index = null): BlockElement
    {
        $multiBlockId = $this->getBlockId();
        if ($multiBlockId !== null) {
            $this->index = $index ?? $this->index;
            $block->blockId("{$this->getBlockId()}.{$this->index}");
            ++$this->index;
        }

        return $block;
    }

    /**
     * @return BlockElement[]
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }

    public function getIterator(): Iterator
    {
        foreach ($this->getBlocks() as $block) {
            yield $block;
        }
    }

    public function validate(): void
    {
        foreach ($this->blocks as $block) {
            $block->validate();
        }
    }

    public function toArray(): array
    {
        return array_map(fn (BlockElement $block): array => $block->toArray(), $this->blocks);
    }

    /**
     * Hydrating virtual blocks cannot be done.
     *
     * Virtual Blocks are a write-only construct. They won't come up during a regular surface hydration. This exception
     * will only occur if someone calls `fromArray` manually on a virtual block class.
     */
    protected function hydrate(HydrationData $data): void
    {
        throw new HydrationException('Cannot hydrate virtual blocks; they have no distinguishable representation');
    }
}
