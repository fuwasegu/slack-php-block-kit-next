<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Blocks;

use SlackPhp\BlockKit\{Element, Exception, HydrationData, Type};
use SlackPhp\BlockKit\Partials\RichTextElements\{RichTextElement, RichTextSection, RichTextList, RichTextPreformatted, RichTextQuote};
use SlackPhp\BlockKit\Partials\RichTextElements\TextElements\{Text, TextElement, Link, Broadcast, Color, Channel, Date, Emoji, User, UserGroup};

class RichText extends BlockElement
{
    /**
     * @var RichTextElement[]
     */
    private array $elements = [];

    public function __construct(?string $blockId = null)
    {
        parent::__construct($blockId);
    }

    /**
     * Add an element
     */
    public function addElement(RichTextElement $element): static
    {
        $this->elements[] = $element->setParent($this);

        return $this;
    }

    /**
     * Set a collection of elements
     *
     * @param RichTextElement[] $elements
     */
    public function setElements(array $elements): static
    {
        $this->elements = [];

        foreach ($elements as $element) {
            $this->addElement($element);
        }

        return $this;
    }

    /**
     * Get the collection of elements
     *
     * @return RichTextElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * Create a new section and add it
     */
    public function newSection(): RichTextSection
    {
        $section = new RichTextSection();
        $this->addElement($section);

        return $section;
    }

    /**
     * Create a new section with text and add it
     */
    public function addText(string $text, ?array $style = null): static
    {
        $section = $this->newSection();
        $textElement = new Text();
        $textElement->text($text);

        if ($style !== null) {
            $textElement->setStyle($style);
        }

        $section->addElement($textElement);

        return $this;
    }

    /**
     * Create a new section with bold text and add it
     */
    public function addBoldText(string $text): static
    {
        return $this->addText($text, ['bold' => true]);
    }

    /**
     * Create a new section with italic text and add it
     */
    public function addItalicText(string $text): static
    {
        return $this->addText($text, ['italic' => true]);
    }

    /**
     * Create a new section with strikethrough text and add it
     */
    public function addStrikeText(string $text): static
    {
        return $this->addText($text, ['strike' => true]);
    }

    /**
     * Create a new list and add it
     *
     * @param string   $style  List style ('bullet' or 'ordered')
     * @param int|null $indent Indent in pixels
     * @param int|null $offset Offset in pixels
     * @param int|null $border Border width in pixels
     */
    public function newList(string $style = 'bullet', ?int $indent = null, ?int $offset = null, ?int $border = null): RichTextList
    {
        $list = new RichTextList();
        $list->setStyle($style);

        if ($indent !== null) {
            $list->setIndent($indent);
        }

        if ($offset !== null) {
            $list->setOffset($offset);
        }

        if ($border !== null) {
            $list->setBorder($border);
        }

        $this->addElement($list);

        return $list;
    }

    /**
     * Create a new bullet list and add it
     *
     * @param int|null $indent Indent in pixels
     * @param int|null $offset Offset in pixels
     * @param int|null $border Border width in pixels
     */
    public function newBulletList(?int $indent = null, ?int $offset = null, ?int $border = null): RichTextList
    {
        return $this->newList('bullet', $indent, $offset, $border);
    }

    /**
     * Create a new ordered list and add it
     *
     * @param int|null $indent Indent in pixels
     * @param int|null $offset Offset in pixels
     * @param int|null $border Border width in pixels
     */
    public function newOrderedList(?int $indent = null, ?int $offset = null, ?int $border = null): RichTextList
    {
        return $this->newList('ordered', $indent, $offset, $border);
    }

    /**
     * Add a code block with the given code text
     *
     * @param string   $code   Code text
     * @param int|null $border Border width in pixels
     */
    public function addCode(string $code, ?int $border = null): static
    {
        $preformatted = $this->newPreformatted($border);
        $preformatted->text($code);

        return $this;
    }

    /**
     * Create a new preformatted block and add it
     *
     * @param int|null $border Border width in pixels
     */
    public function newPreformatted(?int $border = null): RichTextPreformatted
    {
        $preformatted = new RichTextPreformatted();

        if ($border !== null) {
            $preformatted->setBorder($border);
        }

        $this->addElement($preformatted);

        return $preformatted;
    }

    /**
     * Create a new quote block and add it
     *
     * @param int|null $border Border width in pixels
     */
    public function newQuote(?int $border = null): RichTextQuote
    {
        $quote = new RichTextQuote();

        if ($border !== null) {
            $quote->setBorder($border);
        }

        $this->addElement($quote);

        return $quote;
    }

    /**
     * Create a new quote block with text and add it
     *
     * @param string   $text   Quote text
     * @param int|null $border Border width in pixels
     */
    public function addQuote(string $text, ?int $border = null): static
    {
        $quote = $this->newQuote($border);
        $textElement = new Text();
        $textElement->text($text);
        $quote->addElement($textElement);

        return $this;
    }

    /**
     * Create a new section with a link element and add it
     *
     * @param string      $url   Link URL
     * @param string|null $text  Link text (URL is used if omitted)
     * @param array|null  $style Style settings
     */
    public function addLink(string $url, ?string $text = null, ?array $style = null): static
    {
        $section = $this->newSection();
        $link = new Link();
        $link->url($url);

        if ($text !== null) {
            $link->text($text);
        }

        if ($style !== null) {
            $link->setStyle($style);
        }

        $section->addElement($link);

        return $this;
    }

    /**
     * Add a broadcast element to the new section
     *
     * @param string $range Broadcast range ('here', 'channel', 'everyone')
     */
    public function addBroadcast(string $range): static
    {
        if (!in_array($range, ['here', 'channel', 'everyone'], true)) {
            throw new Exception('Invalid broadcast range: %s. Must be one of: here, channel, everyone', [$range]);
        }

        $section = $this->newSection();
        $broadcast = new Broadcast();
        $broadcast->setRange($range);
        $section->addElement($broadcast);

        return $this;
    }

    /**
     * Add a color element to the new section
     *
     * @param string $hexColor 16-digit color code
     */
    public function addColor(string $hexColor): static
    {
        $section = $this->newSection();
        $color = new Color();
        $color->setValue($hexColor);
        $section->addElement($color);

        return $this;
    }

    /**
     * Add a channel element to the new section
     *
     * @param string     $channelId Channel ID
     * @param array|null $style     Style settings (bold, italic, strike, highlight, client_highlight, unlink)
     */
    public function addChannel(string $channelId, ?array $style = null): static
    {
        $section = $this->newSection();
        $channel = new Channel();
        $channel->setChannelId($channelId);

        if ($style !== null) {
            $channel->setStyle($style);
        }

        $section->addElement($channel);

        return $this;
    }

    /**
     * Add a date element to the new section
     *
     * @param int         $timestamp Unix timestamp (in seconds)
     * @param string      $format    Date format
     * @param string|null $url       Link URL
     * @param string|null $fallback  Fallback text
     */
    public function addDate(int $timestamp, string $format, ?string $url = null, ?string $fallback = null): static
    {
        $section = $this->newSection();
        $date = new Date();
        $date->setTimestamp($timestamp)->setFormat($format);

        if ($url !== null) {
            $date->setUrl($url);
        }

        if ($fallback !== null) {
            $date->setFallback($fallback);
        }

        $section->addElement($date);

        return $this;
    }

    /**
     * Add an emoji element to the new section
     *
     * @param string      $name    Emoji name
     * @param string|null $unicode Unicode code point
     */
    public function addEmoji(string $name, ?string $unicode = null): static
    {
        $section = $this->newSection();
        $emoji = new Emoji();
        $emoji->setName($name);

        if ($unicode !== null) {
            $emoji->setUnicode($unicode);
        }

        $section->addElement($emoji);

        return $this;
    }

    /**
     * Add a user element to the new section
     *
     * @param string     $userId User ID
     * @param array|null $style  Style settings (bold, italic, strike, highlight, client_highlight, unlink)
     */
    public function addUser(string $userId, ?array $style = null): static
    {
        $section = $this->newSection();
        $user = new User();
        $user->setUserId($userId);

        if ($style !== null) {
            $user->setStyle($style);
        }

        $section->addElement($user);

        return $this;
    }

    /**
     * Add a user group element to the new section
     *
     * @param string     $usergroupId User group ID
     * @param array|null $style       Style settings (bold, italic, strike, highlight, client_highlight, unlink)
     */
    public function addUserGroup(string $usergroupId, ?array $style = null): static
    {
        $section = $this->newSection();
        $usergroup = new UserGroup();
        $usergroup->setUsergroupId($usergroupId);

        if ($style !== null) {
            $usergroup->setStyle($style);
        }

        $section->addElement($usergroup);

        return $this;
    }

    /**
     * Validate the block
     */
    public function validate(): void
    {
        // Empty elements array is allowed (according to the specification)

        foreach ($this->elements as $element) {
            $element->validate();
        }
    }

    /**
     * Convert the block to an array
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['type'] = Type::RICH_TEXT;

        $elements = [];
        foreach ($this->elements as $element) {
            $elements[] = $element->toArray();
        }

        $data['elements'] = $elements;

        return $data;
    }

    /**
     * Hydrate the block from an array
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('elements')) {
            $elements = $data->useArray('elements');
            foreach ($elements as $element) {
                if (!isset($element['type'])) {
                    throw new Exception('RichText element data must include a type');
                }

                $type = $element['type'];
                $richTextElement = RichTextElement::createFromType($type);
                $richTextElement->hydrate(new HydrationData($element));
                $this->addElement($richTextElement);
            }
        }
    }
}
