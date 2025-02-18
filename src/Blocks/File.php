<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;

class File extends BlockElement
{
    private ?string $externalId = null;

    private ?string $source = null;

    public function __construct(?string $blockId = null, ?string $externalId = null, string $source = 'remote')
    {
        parent::__construct($blockId);

        if ($externalId !== null && $externalId !== '') {
            $this->externalId($externalId);
        }

        if ($source !== '') {
            $this->source($source);
        }
    }

    public function externalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function source(string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function validate(): void
    {
        if ($this->externalId === null || $this->externalId === '') {
            throw new Exception('File must contain "external_id"');
        }

        if ($this->source === null || $this->source === '') {
            throw new Exception('File must contain "source"');
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if (is_string($this->externalId)) {
            $data['external_id'] = $this->externalId;
        }

        if (is_string($this->source)) {
            $data['source'] = $this->source;
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('external_id')) {
            $this->externalId($data->useValue('external_id'));
        }

        if ($data->has('source')) {
            $this->externalId($data->useValue('source'));
        }

        parent::hydrate($data);
    }
}
