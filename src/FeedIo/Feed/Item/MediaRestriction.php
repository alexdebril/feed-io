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

abstract class MediaRestrictionRelationship extends MediaConstant
{
    const Allow = 1;
    const Deny = 2;

    const VALUES = array(
        "allow" => MediaRestrictionRelationship::Allow,
        "deny" => MediaRestrictionRelationship::Deny,
    );
}

abstract class MediaRestrictionType extends MediaConstant
{
    const Country = 1;
    const URI = 2;
    const Sharing = 3;

    const VALUES = array(
        null => MediaRestrictionType::Sharing,
        "country" => MediaRestrictionType::Country,
        "uri" => MediaRestrictionType::URI,
        "sharing" => MediaRestrictionType::Sharing,
    );
}


class MediaRestriction implements MediaRestrictionInterface
{

    /**
     * @var string
     */
    protected $content;

    /**
     * @var int
     */
    protected $type;

    /**
     * @var int
     */
    protected $relationship;


    /**
     * @return string
     */
    public function getContent() : ?string
    {
        return $this->content;
    }

    /**
     * @param  string $content
     * @return MediaRestrictionInterface
     */
    public function setContent(?string $content) : MediaRestrictionInterface
    {
        $this->content = $content;

        return $this;
    }


    /**
     * @return int
     */
    public function getType() : ?int
    {
        return $this->type;
    }

    /**
     * @param  int $type
     * @return MediaRestrictionInterface
     */
    public function setType(?int $type) : MediaRestrictionInterface
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getRelationship() : ?int
    {
        return $this->relationship;
    }

    /**
     * @param  int $relationship
     * @return MediaRestrictionInterface
     */
    public function setRelationship(?int $relationship) : MediaRestrictionInterface
    {
        $this->relationship = $relationship;

        return $this;
    }
}
