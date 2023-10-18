<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks\Virtual;

use SlackPhp\BlockKit\Blocks\Section;
use SlackPhp\BlockKit\Exception;

/**
 * A virtual, multi-block element using sections to create a two-column table.
 *
 * Since regular Fields elements only support 10 items (5 rows), this uses one Fields element per row. This supports as
 * many rows as will fit in the message/surface (which supports up to 50 blocks), all with consistent margins.
 */
class TwoColumnTable extends VirtualBlock
{
    private ?\SlackPhp\BlockKit\Blocks\Section $header = null;

    private bool $hasRows = false;

    public function __construct(
        ?string $blockId = null,
        ?array $rows = null,
        ?array $cols = null,
        ?string $caption = null,
    ) {
        parent::__construct($blockId);

        if ($caption !== null && $caption !== '') {
            $this->caption($caption);
        }

        if ($cols !== null && $cols !== []) {
            [$left, $right] = $cols;
            $this->cols($left, $right);
        }

        if ($rows !== null && $rows !== []) {
            $this->rows($rows);
        }
    }

    /**
     * Sets a caption (text element) at the top of the table.
     */
    public function caption(string $caption): static
    {
        if ($this->header === null) {
            $this->header = new Section();
            $this->prependBlock($this->header);
        }

        $this->header->mrkdwnText($caption);

        return $this;
    }

    /**
     * Sets the left and right column headers.
     *
     * Automatically applies a bold to the header text elements.
     */
    public function cols(string $left, string $right): static
    {
        if ($this->header === null) {
            $this->header = new Section();
            $this->prependBlock($this->header);
        }

        $this->header->fieldList(["*{$left}*", "*{$right}*"]);

        return $this;
    }

    /**
     * Adds a row (with a left and right value) to the table.
     *
     * @return static
     */
    public function row(string $left, string $right)
    {
        $row = new Section();
        $row->fieldList([$left, $right]);
        $this->hasRows = true;

        return $this->appendBlock($row);
    }

    /**
     * Adds multiple rows to the table.
     *
     * Supports list-format (e.g., [[$left, $right], ...]) or map-format (e.g., [$left => $right, ...]) as input.
     */
    public function rows(array $rows): static
    {
        if (isset($rows[0])) {
            foreach ($rows as [$left, $right]) {
                $this->row($left, $right);
            }
        } else {
            foreach ($rows as $left => $right) {
                $this->row($left, $right);
            }
        }

        return $this;
    }

    public function validate(): void
    {
        if (!$this->hasRows) {
            throw new Exception('TwoColumnTable must contain rows');
        }

        parent::validate();
    }
}
