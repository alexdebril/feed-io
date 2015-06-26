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

use FeedIo\Feed\NodeInterface;
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
        $mediaRule = new Media();
        $mediaRule->setUrlAttributeName('href');
        $this->ruleSet = new RuleSet(new Link('related'));
        $this->ruleSet->add($mediaRule);
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return mixed
     */
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        if ($element->hasAttribute('rel')) {
            $this->ruleSet->get($element->getAttribute('rel'))->setProperty($node, $element);
        } else {
            $this->ruleSet->getDefault()->setProperty($node, $element);
        }

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     * @return \DomElement
     */
    public function createElement(\DomDocument $document, NodeInterface $node)
    {
        return $this->ruleSet->getDefault()->createElement($document, $node);
    }
}
