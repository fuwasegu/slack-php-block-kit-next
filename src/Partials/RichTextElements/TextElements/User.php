<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class User extends TextElement
{
    private ?string $userId = null;

    /**
     * ユーザーIDを設定する
     */
    public function userId(string $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * ユーザーIDを取得する
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'user';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->userId === null || $this->userId === '') {
            throw new Exception('User element must have a user_id value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['user_id'] = $this->userId;

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('user_id')) {
            $this->userId($data->useValue('user_id'));
        }
    }
}
