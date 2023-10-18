<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit;

use JsonSerializable;
use Throwable;

abstract class Element implements JsonSerializable
{
    /**
     * @var Element|null
     */
    protected $parent;

    /**
     * @var array
     */
    protected $extra;

    /**
     * @return static
     */
    public static function new()
    {
        return new static();
    }

    final public function getParent(): ?self
    {
        return $this->parent;
    }

    /**
     * @return static
     */
    final public function setParent(self $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    public function getType(): string
    {
        return Type::mapClass(static::class);
    }

    /**
     * Allows setting arbitrary extra fields on an element.
     *
     * @param  mixed  $value
     * @return static
     */
    final public function setExtra(string $key, $value)
    {
        $this->extra[$key] = $value;

        return $this;
    }

    /**
     * Allows you to "tap" into the fluent syntax with a callable.
     *
     *     $element = Elem::new()
     *         ->foo('bar')
     *         ->tap(function (Elem $elem) {
     *             $elem->newSubElem()->fizz('buzz');
     *         });
     *
     * @return static
     */
    final public function tap(callable $tap)
    {
        $tap($this);

        return $this;
    }

    /**
     * Allows you to "tap" into the fluent syntax with a callable, if the condition is met.
     *
     *     $element = Elem::new()
     *         ->foo('bar')
     *         ->tapIf($needsSubElem, function (Elem $elem) {
     *             $elem->newSubElem()->fizz('buzz');
     *         });
     *
     * @return static
     */
    final public function tapIf(bool $condition, callable $tap)
    {
        if ($condition) {
            $tap($this);
        }

        return $this;
    }

    /**
     * @throws Exception if the block kit item is invalid (e.g., missing data).
     */
    abstract public function validate(): void;

    public function toArray(): array
    {
        $this->validate();
        $type = $this->getType();

        $data = !in_array($type, Type::HIDDEN_TYPES, true) ? compact('type') : [];

        foreach ($this->extra ?? [] as $key => $value) {
            $data[$key] = $value instanceof self ? $value->toArray() : $value;
        }

        return $data;
    }

    public function toJson(bool $prettyPrint = false): string
    {
        $opts = JSON_THROW_ON_ERROR;
        if ($prettyPrint) {
            $opts |= JSON_PRETTY_PRINT;
        }

        return (string)json_encode($this, $opts);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * @return static
     */
    final public static function fromJson(string $json)
    {
        try {
            $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        } catch (Throwable $err) {
            throw new HydrationException('JSON error (%s) hydrating %s', [$err->getMessage(), static::class], $err);
        }

        return static::fromArray($data);
    }

    /**
     * @return static
     */
    final public static function fromArray(array $data)
    {
        $data = new HydrationData($data);

        // Determine element class to hydrate.
        // - If a type is present, map the type to the class.
        // - Type-mapped class must be the same as or a subclass of the late-static-bound class.
        // - If no type present, use the late-static-bound class.
        $class = static::class;
        if ($data->has('type')) {
            $typeClass = Type::mapType((string)$data->get('type'));
            if (is_a($typeClass, $class, true)) {
                $class = $typeClass;
            } else {
                throw new Exception('Element class mismatch in fromArray: %s is not a %s', [$typeClass, $class]);
            }
        }

        /** @var static $element */
        $element = new $class();
        $element->hydrate($data);

        return $element;
    }

    /**
     * @internal used by fromArray implementations
     */
    protected function hydrate(HydrationData $data): void
    {
        $type = $data->useValue('type');

        $class = static::class;
        if (is_string($type) && Type::mapType($type) !== $class) {
            throw new Exception('[Hydration] Type %s does not map to class %s.', [$type, $class]);
        }

        foreach ($data->getExtra() as $key => $value) {
            $this->setExtra($key, $value);
        }

        $this->validate();
    }
}
