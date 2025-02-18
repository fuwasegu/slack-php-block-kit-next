<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\Element;
use SlackPhp\BlockKit\HydrationData;

abstract class BlockElement extends Element
{
    private ?string $blockId = null;

    public function __construct(?string $blockId = null)
    {
        if ($blockId !== null && $blockId !== '') {
            $this->blockId($blockId);
        }
    }

    public function blockId(string $blockId): static
    {
        $this->blockId = $blockId;

        return $this;
    }

    public function getBlockId(): ?string
    {
        return $this->blockId;
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (is_string($this->blockId) && $this->blockId !== '') {
            $data['block_id'] = $this->blockId;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('block_id')) {
            $this->blockId($data->useValue('block_id'));
        }

        parent::hydrate($data);
    }
}
