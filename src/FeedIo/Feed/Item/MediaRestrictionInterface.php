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

interface MediaRestrictionInterface
{
    /**
     * @return string
     */
    public function getContent() : ?string;

    /**
     * @param  string $content
     * @return MediaRestrictionInterface
     */
    public function setContent(?string $content) : MediaRestrictionInterface;


    /**
     * @return int
     */
    public function getType() : ?int;

    /**
     * @param  string $type
     * @return MediaRestrictionInterface
     */
    public function setType(?int $type) : MediaRestrictionInterface;


    /**
     * @return int
     */
    public function getRelationship() : ?int;

    /**
     * @param  string $relationship
     * @return MediaRestrictionInterface
     */
    public function setRelationship(?int $relationship) : MediaRestrictionInterface;
}
