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
     * 要素を追加する
     */
    public function addElement(RichTextElement $element): static
    {
        $this->elements[] = $element->setParent($this);

        return $this;
    }

    /**
     * 要素のコレクションを設定する
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
     * 要素のコレクションを取得する
     *
     * @return RichTextElement[]
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    /**
     * 新しいセクションを作成して追加する
     */
    public function newSection(): RichTextSection
    {
        $section = new RichTextSection();
        $this->addElement($section);

        return $section;
    }

    /**
     * テキストを含む新しいセクションを作成して追加する
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
     * 太字テキストを含む新しいセクションを作成して追加する
     */
    public function addBoldText(string $text): static
    {
        return $this->addText($text, ['bold' => true]);
    }

    /**
     * 斜体テキストを含む新しいセクションを作成して追加する
     */
    public function addItalicText(string $text): static
    {
        return $this->addText($text, ['italic' => true]);
    }

    /**
     * 取り消し線テキストを含む新しいセクションを作成して追加する
     */
    public function addStrikeText(string $text): static
    {
        return $this->addText($text, ['strike' => true]);
    }

    /**
     * 新しいリストを作成して追加する
     *
     * @param string   $style  リストのスタイル ('bullet' または 'ordered')
     * @param int|null $indent インデントのピクセル数
     * @param int|null $offset オフセットのピクセル数
     * @param int|null $border ボーダーの太さ（ピクセル単位）
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
     * 新しい箇条書きリストを作成して追加する
     *
     * @param int|null $indent インデントのピクセル数
     * @param int|null $offset オフセットのピクセル数
     * @param int|null $border ボーダーの太さ（ピクセル単位）
     */
    public function newBulletList(?int $indent = null, ?int $offset = null, ?int $border = null): RichTextList
    {
        return $this->newList('bullet', $indent, $offset, $border);
    }

    /**
     * 新しい番号付きリストを作成して追加する
     *
     * @param int|null $indent インデントのピクセル数
     * @param int|null $offset オフセットのピクセル数
     * @param int|null $border ボーダーの太さ（ピクセル単位）
     */
    public function newOrderedList(?int $indent = null, ?int $offset = null, ?int $border = null): RichTextList
    {
        return $this->newList('ordered', $indent, $offset, $border);
    }

    /**
     * 新しいコードブロックを作成して追加する
     *
     * @param string   $code   コードテキスト
     * @param int|null $border ボーダーの太さ（ピクセル単位）
     */
    public function addCode(string $code, ?int $border = null): static
    {
        $preformatted = $this->newPreformatted($border);
        $preformatted->text($code);

        return $this;
    }

    /**
     * 新しいプリフォーマットブロックを作成して追加する
     *
     * @param int|null $border ボーダーの太さ（ピクセル単位）
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
     * 新しい引用ブロックを作成して追加する
     *
     * @param int|null $border ボーダーの太さ（ピクセル単位）
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
     * 引用テキストを含む新しい引用ブロックを作成して追加する
     *
     * @param string   $text   引用テキスト
     * @param int|null $border ボーダーの太さ（ピクセル単位）
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
     * リンク要素を含む新しいセクションを作成して追加する
     *
     * @param string      $url   リンクのURL
     * @param string|null $text  リンクのテキスト（省略時はURLが使用される）
     * @param array|null  $style スタイル設定
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
     * ブロードキャスト要素を含む新しいセクションを作成して追加する
     *
     * @param string $range ブロードキャストの範囲 ('here', 'channel', 'everyone')
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
     * カラー要素を含む新しいセクションを作成して追加する
     *
     * @param string $hexColor 16進数のカラーコード
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
     * チャンネル要素を含む新しいセクションを作成して追加する
     *
     * @param string     $channelId チャンネルID
     * @param array|null $style     スタイル設定 (bold, italic, strike, highlight, client_highlight, unlink)
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
     * 日付要素を含む新しいセクションを作成して追加する
     *
     * @param int         $timestamp Unix タイムスタンプ（秒単位）
     * @param string      $format    日付フォーマット
     * @param string|null $url       リンクURL
     * @param string|null $fallback  フォールバックテキスト
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
     * 絵文字要素を含む新しいセクションを作成して追加する
     *
     * @param string      $name    絵文字名
     * @param string|null $unicode Unicode コードポイント
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
     * ユーザー要素を含む新しいセクションを作成して追加する
     *
     * @param string     $userId ユーザーID
     * @param array|null $style  スタイル設定 (bold, italic, strike, highlight, client_highlight, unlink)
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
     * ユーザーグループ要素を含む新しいセクションを作成して追加する
     *
     * @param string     $usergroupId ユーザーグループID
     * @param array|null $style       スタイル設定 (bold, italic, strike, highlight, client_highlight, unlink)
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
     * ブロックを検証する
     */
    public function validate(): void
    {
        // elements は空配列も許可する（仕様に基づく修正）

        foreach ($this->elements as $element) {
            $element->validate();
        }
    }

    /**
     * ブロックを配列に変換する
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
     * 配列からブロックを生成する
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
