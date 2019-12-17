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

abstract class MediaCreditScheme extends MediaConstant
{
    const URN_EBU = 1;
    const URN_YVS = 2;

    const VALUES = array(
        null => MediaCreditScheme::URN_EBU,
        "urn:ebu" => MediaCreditScheme::URN_EBU,
        "urn:yvs" => MediaCreditScheme::URN_YVS,
    );
}

class MediaCredit
{
    /**
     * @var int
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $role;

    /**
     * @var string
     */
    protected $value;

    /**
     * @param  int $scheme
     * @return MediaCredit
     */
    public function setScheme(int $scheme) : MediaCredit
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return int
     */
    public function getScheme() : ? int
    {
        return $this->scheme;
    }

    /**
     * @param  string $role
     * @return MediaCredit
     */
    public function setRole(?string $role) : MediaCredit
    {
        $this->role = $role;

        return $this;
    }

    public function getRole() : ? string
    {
        return $this->role;
    }

    /**
     * @param  string $value
     * @return MediaCredit
     */
    public function setValue(string $value) : MediaCredit
    {
        $this->value = $value;

        return $this;
    }

    public function getValue() : string
    {
        return $this->value;
    }
}
