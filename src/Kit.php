<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit;

/**
 * Kit act as a static façade to the whole block kit library.
 *
 * It provides methods to instantiate each type of surface, preview a surface using Slack's Block Kit Builder, and
 * access the singleton Config and Formatter instances. The Kit's instances of Config and Formatter are used throughout
 * the rest of the library.
 */
abstract class Kit
{
    private static ?Config $config = null;

    private static ?Formatter $formatter = null;

    private static ?Previewer $previewer = null;

    public static function newAppHome(): Surfaces\AppHome
    {
        return new Surfaces\AppHome();
    }

    public static function newMessage(): Surfaces\Message
    {
        return new Surfaces\Message();
    }

    public static function newModal(): Surfaces\Modal
    {
        return new Surfaces\Modal();
    }

    public static function newRichText(?string $blockId = null): Blocks\RichText
    {
        return new Blocks\RichText($blockId);
    }

    public static function config(): Config
    {
        if (!self::$config instanceof Config) {
            self::$config = Config::new();
        }

        return self::$config;
    }

    public static function formatter(): Formatter
    {
        if (!self::$formatter instanceof Formatter) {
            self::$formatter = Formatter::new();
        }

        return self::$formatter;
    }

    public static function preview(Surfaces\Surface $surface): string
    {
        if (!self::$previewer instanceof Previewer) {
            self::$previewer = Previewer::new();
        }

        return self::$previewer->preview($surface);
    }
}
