<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule\Atom;


use FeedIo\Feed\ItemInterface;
use FeedIo\RuleAbstract;
use FeedIo\RuleSet;
use FeedIo\Rule\Media;

class LinkNode extends RuleAbstract
{

    const NODE_NAME = 'link';

    /**
     * @var \FeedIo\RuleSet
     */
    protected $ruleSet;

    /**
     * @param string $nodeName
     */
    public function __construct($nodeName = null)
    {
        parent::__construct($nodeName);
        $mediaRule = new Media;
        $mediaRule->setUrlAttributeName('href');
        $this->ruleSet = new RuleSet(new Link('related'));
        $this->ruleSet->add($mediaRule);
    }

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @return mixed
     */
    public function setProperty(ItemInterface $item, \DOMElement $element)
    {
        if ( $element->hasAttribute('rel') ) {
            $this->ruleSet->get($element->getAttribute('rel'))->setProperty($item, $element);
        } else {
            $this->ruleSet->getDefault()->setProperty($item, $element);
        }

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param \DomDocument $document
     * @param ItemInterface $item
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, ItemInterface $item)
    {
        return $this->ruleSet->getDefault()->createElement($document, $item);
    }

}
