<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit;

use function date;
use function time;

/**
 * Formatter consists of text formatting helpers to support the use of Slack's Mrkdwn format in messages, modals, etc.
 *
 * Some characters ("<", ">", "&") should be escaped when they are not a part of formatting that requires those
 * characters. The formatting helpers in this class automatically escape input in situations where the text is place
 * within angle brackets, such as with dates and links.
 *
 * Notes:
 * - `Formatter::new()` returns a singleton instance.
 * - You can also call the methods using static notation (e.g., `Formatter::atHere()`).
 */
final class Formatter
{
    public const DATE = '{date}';
    public const DATE_LONG = '{date_long}';
    public const DATE_LONG_PRETTY = '{date_long_pretty}';
    public const DATE_NUM = '{date_num}';
    public const DATE_PRETTY = '{date_pretty}';
    public const DATE_SHORT = '{date_short}';
    public const DATE_SHORT_PRETTY = '{date_short_pretty}';
    public const TIME = '{time}';
    public const TIME_SECS = '{time_secs}';

    public static function new(): self
    {
        return new self();
    }

    /**
     * Escapes ambiguous characters to their HTML entities.
     */
    public function escape(string $text): string
    {
        return \strtr($text, [
            '&' => '&amp;',
            '<' => '&lt;',
            '>' => '&gt;',
        ]);
    }

    /**
     * Performs a string interpolation by substituting keys (in curly braces) for their values.
     */
    public function sub(string $text, array $values): string
    {
        $replacements = [];
        foreach ($values as $key => $value) {
            $replacements["{{$key}}"] = $value;
        }

        return \strtr($text, $replacements);
    }

    // region Helpers for @here, @channel, and @everyone mentions.
    public function atChannel(): string
    {
        return '<!channel>';
    }

    public function atEveryone(): string
    {
        return '<!everyone>';
    }

    public function atHere(): string
    {
        return '<!here>';
    }
    // endregion

    // region Helpers for mentioning/linking specific channels, users, or user groups.
    public function channel(string $id): string
    {
        return "<#{$id}>";
    }

    public function user(string $id): string
    {
        return "<@{$id}>";
    }

    public function userGroup(string $id): string
    {
        return "<!subteam^{$id}>";
    }
    // endregion

    // region Helpers for basic text formatting (B/I/S) and links.
    public function bold(string $text): string
    {
        return "*{$text}*";
    }

    public function code(string $text): string
    {
        return "`{$this->escape($text)}`";
    }

    public function italic(string $text): string
    {
        return "_{$text}_";
    }

    public function strike(string $text): string
    {
        return "~{$text}~";
    }

    public function link(string $url, ?string $text = null): string
    {
        return isset($text) ? "<{$this->escape($url)}|{$this->escape($text)}>" : "<{$this->escape($url)}>";
    }

    public function emailLink(string $email, ?string $text = null): string
    {
        return $this->link("mailto:{$email}", $text);
    }
    // endregion

    // region Helpers for multi-line content blocks like lists and quotes.

    public function blockQuote(array|string $lines): string
    {
        return $this->lines($this->explode($lines), '> ', false);
    }

    public function bulletedList(array|string $items, string $bullet = '•'): string
    {
        return $this->lines($this->explode($items), "{$bullet} ");
    }

    public function codeBlock(string $text): string
    {
        return "```\n{$this->escape($text)}\n```";
    }

    public function numberedList(array|string $items): string
    {
        $index = 0;

        return $this->lines($this->explode($items), static function (string $item) use (&$index): string {
            ++$index;

            return "{$index}. {$item}";
        });
    }

    /**
     * Takes a list of lines/strings, and concatenates them with newlines, filtering out any empty lines.
     *
     * Optionally applies a prefix to each line. You can use a closure if the prefix varies per line.
     */
    public function lines(array $lines, null|callable|string $prefix = null, bool $filter = true): string
    {
        if (\is_string($prefix)) {
            $prefix = static fn (string $value): string => "{$prefix}{$value}";
        }

        if (\is_callable($prefix)) {
            $lines = \array_map($prefix, $lines);
        }

        if ($filter) {
            $lines = \array_filter($lines, static fn ($line): bool => $line !== null && $line !== '');
        }

        return \implode("\n", $lines) . "\n";
    }
    // endregion

    // region Helpers for formatting dates and times.
    /**
     * Formats a timestamp as a date in mrkdwn.
     *
     * @param int|null    $timestamp Timestamp to format. Defaults to now.
     * @param string      $format    Format name supported by Slack. Defaults to "{date}".
     * @param string|null $fallback  Fallback text for old Slack clients. Defaults to an ISO-formatted timestamp.
     * @param string|null $link      URL, if the date is to act as a link
     * @see https://api.slack.com/reference/surfaces/formatting#date-formatting
     */
    public function date(
        ?int $timestamp = null,
        string $format = self::DATE,
        ?string $fallback = null,
        ?string $link = null,
    ): string {
        $timestamp ??= \time();
        $fallback = $this->escape($fallback ?? \date('c', $timestamp));
        $link = $link ? "^{$this->escape($link)}" : '';

        return "<!date^{$timestamp}^{$this->escape($format)}{$link}|{$fallback}>";
    }

    /**
     * Formats a timestamp as a time in mrkdwn.
     *
     * Equivalent to Formatter::date(), but uses the TIME format as default.
     *
     * @param int|null    $timestamp Timestamp to format. Defaults to now.
     * @param string      $format    Format name supported by Slack. Defaults to "{time}".
     * @param string|null $fallback  Fallback text for old Slack clients. Defaults to an ISO-formatted timestamp.
     * @param string|null $link      URL, if the time is to act as a link
     */
    public function time(
        ?int $timestamp = null,
        string $format = self::TIME,
        ?string $fallback = null,
        ?string $link = null,
    ): string {
        return $this->date($timestamp, $format, $fallback, $link);
    }
    // endregion

    /**
     * Ensures the provided items are an array.
     *
     * Explodes strings on "\n" if a string is provided.
     */
    private function explode(array|string $items): array
    {
        if (\is_string($items)) {
            return \explode("\n", $items);
        }

        return $items;
    }
}
