<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

abstract class MediaStatusValue extends MediaConstant
{
    const Active = 1;
    const Blocked = 2;
    const Deleted = 3;

    const VALUES = array(
        "active" => MediaStatusValue::Active,
        "blocked" => MediaStatusValue::Blocked,
        "deleted" => MediaStatusValue::Deleted,
    );
}


abstract class MediaRightsStatus extends MediaConstant
{
    const UserCreated = 1;
    const Official = 2;

    const VALUES = array(
        "usercreated" => MediaRightsStatus::UserCreated,
        "official" => MediaRightsStatus::Official,
    );
}


class MediaStatus implements MediaStatusInterface
{
    /**
     * @var int
     */
    protected $value;

    /**
     * @var string
     */
    protected $reason;

    /**
     * @return int
     */
    public function getValue() : ?int
    {
        return $this->value;
    }

    /**
     * @param  int $value
     * @return MediaValueInterface
     */
    public function setValue(?int $value) : MediaStatusInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getReason() : ?string
    {
        return $this->reason;
    }

    /**
     * @param  string $reason
     * @return MediaStatusInterface
     */
    public function setReason(?string $reason) : MediaStatusInterface
    {
        $this->reason = $reason;

        return $this;
    }
}
