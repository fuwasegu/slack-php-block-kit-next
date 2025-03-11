<?php

declare(strict_types=1);

namespace SlackPhp\BlockKit\Partials\RichTextElements\TextElements;

use SlackPhp\BlockKit\{Exception, HydrationData};

class UserGroup extends TextElement
{
    private ?string $usergroupId = null;

    /**
     * ユーザーグループIDを設定する
     */
    public function usergroupId(string $usergroupId): static
    {
        $this->usergroupId = $usergroupId;

        return $this;
    }

    /**
     * ユーザーグループIDを取得する
     */
    public function getUsergroupId(): ?string
    {
        return $this->usergroupId;
    }

    /**
     * 要素の型を取得する
     */
    public function getElementType(): string
    {
        return 'usergroup';
    }

    /**
     * 要素を検証する
     */
    public function validate(): void
    {
        if ($this->usergroupId === null || $this->usergroupId === '') {
            throw new Exception('UserGroup element must have a usergroup_id value');
        }
    }

    /**
     * 要素を配列に変換する
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        $data['usergroup_id'] = $this->usergroupId;

        return $data;
    }

    /**
     * 配列から要素を生成する
     */
    protected function hydrate(HydrationData $data): void
    {
        parent::hydrate($data);

        if ($data->has('usergroup_id')) {
            $this->usergroupId($data->useValue('usergroup_id'));
        }
    }
}
