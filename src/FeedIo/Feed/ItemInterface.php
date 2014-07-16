<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed;

use FeedIo\Feed\Item\OptionalFieldsInterface;

/**
 * Interface ItemInterface
 * Represents news items
 * each mandatory field has its own setter and getter
 * other fields are handled using set/get
 * @package FeedIo
 */
interface ItemInterface extends NodeInterface
{
    /**
     * Returns the item's optional fields
     * @return OptionalFieldsInterface
     */
    public function getOptionalFields();

    /**
     * @param OptionalFieldsInterface $optionalFields
     * @return $this
     */
    public function setOptionalFields(OptionalFieldsInterface $optionalFields);
} 