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
    /**
     * @var string
     */
    private $callbackId;

    /**
     * @var string
     */
    private $externalId;

    /**
     * @var string
     */
    private $privateMetadata;

    /**
     * @return static
     */
    public function callbackId(string $callbackId)
    {
        $this->callbackId = $callbackId;

        return $this;
    }

    /**
     * @return static
     */
    public function externalId(string $externalId)
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return static
     */
    public function privateMetadata(string $privateMetadata)
    {
        $this->privateMetadata = $privateMetadata;

        return $this;
    }

    /**
     * Encodes the provided associative array of data into a string for `private_metadata`.
     *
     * Note: Can be decoded using `base64_decode()` and `parse_str()`.
     *
     * @return static
     */
    public function encodePrivateMetadata(array $data)
    {
        return $this->privateMetadata(\base64_encode(\http_build_query($data)));
    }

    public function toArray(): array
    {
        $data = [];

        if (!empty($this->callbackId)) {
            $data['callback_id'] = $this->callbackId;
        }

        if (!empty($this->externalId)) {
            $data['external_id'] = $this->externalId;
        }

        if (!empty($this->privateMetadata)) {
            $data['private_metadata'] = $this->privateMetadata;
        }

        $data += parent::toArray();

        return $data;
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
