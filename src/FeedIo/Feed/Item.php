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


use FeedIo\Feed\Item\OptionalFields;
use FeedIo\Feed\Item\OptionalFieldsInterface;

class Item extends Node implements ItemInterface
{
    /**
     * @var \FeedIo\Feed\Item\OptionalFieldsInterface
     */
    protected $optionalFields;

    /**
     * @param OptionalFieldsInterface $optionalFields
     */
    function __construct(OptionalFieldsInterface $optionalFields = null)
    {
        $this->optionalFields = is_null( $optionalFields) ? new OptionalFields():$optionalFields;
    }

    /**
     * Returns the item's optional fields
     * @return OptionalFieldsInterface
     */
    public function getOptionalFields()
    {
        return $this->optionalFields;
    }

    /**
     * @param OptionalFieldsInterface $optionalFields
     * @return $this
     */
    public function setOptionalFields(OptionalFieldsInterface $optionalFields)
    {
        $this->optionalFields = $optionalFields;

        return $this;
    }

}