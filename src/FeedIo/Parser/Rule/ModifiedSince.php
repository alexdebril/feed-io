<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser\Rule;


use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\DateTimeBuilder;
use FeedIo\Parser\RuleAbstract;

class ModifiedSince extends RuleAbstract
{
    const NODE_NAME = 'pubDate';

    /**
     * @var \FeedIo\Parser\DateTimeBuilder
     */
    protected $dateTimeBuilder;

    /**
     * @param DateTimeBuilder $dateTimeBuilder
     */
    public function __construct(DateTimeBuilder $dateTimeBuilder)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

    /**
     * @return \FeedIo\Parser\DateTimeBuilder
     */
    public function getDateTimeBuilder()
    {
        return $this->dateTimeBuilder;
    }

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function set(ItemInterface $item, \DOMElement $element)
    {
        $item->setLastModified($this->dateTimeBuilder->convertToDateTime($element->nodeValue));

        return $this;
    }

}
