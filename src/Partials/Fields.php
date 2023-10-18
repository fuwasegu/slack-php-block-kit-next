<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials;

use SlackPhp\BlockKit\{Element, Exception, HydrationData};

class Fields extends Element
{
    /**
     * @var Text[]
     */
    private array $fields = [];

    /**
     * @param Text[]|string[] $fields
     */
    public function __construct(array $fields = [])
    {
        if ($fields !== []) {
            $this->populate($fields);
        }
    }

    public function add(Text $field): static
    {
        if (count($this->fields) >= 10) {
            throw new Exception('Cannot have more than 10 fields');
        }

        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param Text[]|string[] $fields
     */
    public function populate(array $fields = []): static
    {
        foreach ($fields as $field) {
            if (!$field instanceof Text) {
                $field = new MrkdwnText($field);
            }

            $this->add($field);
        }

        return $this;
    }

    public function validate(): void
    {
        if ($this->fields === []) {
            throw new Exception('Fields component must have at least one field.');
        }

        foreach ($this->fields as $field) {
            $field->validate();
        }
    }

    public function toArray(): array
    {
        $fields = [];
        foreach ($this->fields as $field) {
            $fields[] = $field->toArray();
        }

        return parent::toArray() + $fields;
    }

    protected function hydrate(HydrationData $data): void
    {
        foreach ($data->useElements(null) as $field) {
            $this->add(Text::fromArray($field));
        }

        parent::hydrate($data);
    }
}
