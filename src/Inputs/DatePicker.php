<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Confirm;
use SlackPhp\BlockKit\Partials\PlainText;
use DateTimeImmutable;

class DatePicker extends InputElement
{
    use HasConfirm;
    use HasPlaceholder;

    private const DATE_FORMAT = 'Y-m-d';

    private ?string $initialDate = null;

    public function initialDate(string $date): static
    {
        $dateTime = DateTimeImmutable::createFromFormat(self::DATE_FORMAT, $date);
        if (!$dateTime) {
            throw new Exception('Date was formatted incorrectly (must be Y-m-d)');
        }

        $this->initialDate = $dateTime->format(self::DATE_FORMAT);

        return $this;
    }

    public function validate(): void
    {
        if ($this->placeholder instanceof PlainText) {
            $this->placeholder->validate();
        }

        if ($this->confirm instanceof Confirm) {
            $this->confirm->validate();
        }
    }

    public function toArray(): array
    {
        $data = parent::toArray();

        if ($this->initialDate !== null && $this->initialDate !== '') {
            $data['initial_date'] = $this->initialDate;
        }

        if ($this->placeholder instanceof PlainText) {
            $data['placeholder'] = $this->placeholder->toArray();
        }

        if ($this->confirm instanceof Confirm) {
            $data['confirm'] = $this->confirm->toArray();
        }

        return $data;
    }

    protected function hydrate(HydrationData $data): void
    {
        if ($data->has('initial_date')) {
            $this->initialDate($data->useValue('initial_date'));
        }

        if ($data->has('placeholder')) {
            $this->setPlaceholder(PlainText::fromArray($data->useElement('placeholder')));
        }

        if ($data->has('confirm')) {
            $this->setConfirm(Confirm::fromArray($data->useElement('confirm')));
        }

        parent::hydrate($data);
    }
}
