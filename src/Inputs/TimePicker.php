<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Inputs;

use SlackPhp\BlockKit\Exception;
use SlackPhp\BlockKit\HydrationData;
use SlackPhp\BlockKit\Partials\Confirm;
use SlackPhp\BlockKit\Partials\PlainText;
use DateTimeImmutable;

class TimePicker extends InputElement
{
    use HasConfirm;
    use HasPlaceholder;

    private const TIME_FORMAT = 'H:i';

    private ?string $initialTime = null;

    public function initialTime(string $time): static
    {
        $dateTime = DateTimeImmutable::createFromFormat(self::TIME_FORMAT, $time);
        if (!$dateTime) {
            throw new Exception('Time was formatted incorrectly (must be H:i)');
        }

        $this->initialTime = $dateTime->format(self::TIME_FORMAT);

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

        if ($this->initialTime !== null && $this->initialTime !== '') {
            $data['initial_time'] = $this->initialTime;
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
        if ($data->has('initial_time')) {
            $this->initialTime($data->useValue('initial_time'));
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
