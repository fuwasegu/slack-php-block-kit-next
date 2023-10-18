<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit;

/**
 * @internal used by fromArray implementations
 */
class HydrationData
{
    /**
     * @var array<string, bool>
     */
    private array $consumed = [];

    /**
     * HydrationData constructor.
     */
    public function __construct(
        /**
         * @var array<string, mixed>
         */
        private array $data,
    ) {
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    /**
     * @return mixed
     */
    public function get(string $key, mixed $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * @return mixed
     */
    public function useValue(string $key, mixed $default = null)
    {
        $this->consumed[$key] = true;

        return $this->get($key, $default);
    }

    /**
     * @return array<int, mixed>
     */
    public function useArray(?string $key): array
    {
        if ($key === null) {
            $this->consumed += array_fill_keys(array_keys($this->data), true);

            return array_values($this->data);
        }

        $this->consumed[$key] = true;

        return $this->data[$key] ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function useElements(?string $key): array
    {
        return $this->useArray($key);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function useElement(string $key): ?array
    {
        $this->consumed[$key] = true;

        return $this->data[$key] ?? null;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtra(): array
    {
        return array_diff_key($this->data, $this->consumed);
    }
}
