<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;


use FeedIo\Feed\ItemInterface;

abstract class RuleAbstract
{
    const NODE_NAME = 'node';

    /**
     * @var string
     */
    protected $nodeName;

    /**
     * @param string $nodeName
     */
    public function __construct($nodeName = null)
    {
        $this->nodeName = is_null($nodeName) ? static::NODE_NAME:$nodeName;
    }

    /**
     * @return string
     */
    public function getNodeName()
    {
        return $this->nodeName;
    }

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return mixed
     */
    abstract public function setFromElement(ItemInterface $item, \DOMElement $element);
}
