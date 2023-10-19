<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Surfaces;

use SlackPhp\BlockKit\HydrationData;

/**
 * View represents the commonalities between the Modal and App Home surfaces.
 *
 * Modal and App Home surfaces are sometimes collectively called "views" in Slack documentation and APIs.
 */
abstract class View extends Surface
{
    private ?string $callbackId = null;

    private ?string $externalId = null;

    private ?string $privateMetadata = null;

    public function callbackId(string $callbackId): static
    {
        $this->callbackId = $callbackId;

        return $this;
    }

    public function externalId(string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function privateMetadata(string $privateMetadata): static
    {
        $this->privateMetadata = $privateMetadata;

        return $this;
    }

    /**
     * Encodes the provided associative array of data into a string for `private_metadata`.
     *
     * Note: Can be decoded using `base64_decode()` and `parse_str()`.
     */
    public function encodePrivateMetadata(array $data): static
    {
        return $this->privateMetadata(\base64_encode(\http_build_query($data)));
    }

    public function toArray(): array
    {
        $data = [];

        if ($this->callbackId !== null && $this->callbackId !== '') {
            $data['callback_id'] = $this->callbackId;
        }

        if ($this->externalId !== null && $this->externalId !== '') {
            $data['external_id'] = $this->externalId;
        }

        if ($this->privateMetadata !== null && $this->privateMetadata !== '') {
            $data['private_metadata'] = $this->privateMetadata;
        }

        return $data + parent::toArray();
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('callback_id')) {
            $this->callbackId($data->useValue('callback_id'));
        }

        if ($data->has('external_id')) {
            $this->externalId($data->useValue('external_id'));
        }

        if ($data->has('private_metadata')) {
            $this->privateMetadata($data->useValue('private_metadata'));
        }

        parent::hydrate($data);
    }
}
