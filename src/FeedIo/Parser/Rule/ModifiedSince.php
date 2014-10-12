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
use FeedIo\Parser\Date;
use FeedIo\Parser\RuleAbstract;

class ModifiedSince extends RuleAbstract
{
    const NODE_NAME = 'pubDate';

    /**
     * @var \FeedIo\Parser\Date
     */
    protected $date;

    /**
     * @param Date $date
     */
    public function __construct(Date $date)
    {
        $this->date = $date;
    }

    /**
     * @return \FeedIo\Parser\Date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return $this
     */
    public function set(ItemInterface $item, \DOMElement $element)
    {
        $item->setLastModified($this->date->convertToDateTime($element->nodeValue));

        return $this;
    }

}
