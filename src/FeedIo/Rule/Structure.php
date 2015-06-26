<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;
use FeedIo\RuleSet;

class Structure extends RuleAbstract
{

    const NODE_NAME = 'structure';

    /**
     * @var \FeedIo\RuleSet
     */
    protected $ruleSet;

    /**
     * @param string  $nodeName
     * @param RuleSet $ruleSet
     */
    public function __construct($nodeName = null, $ruleSet = null)
    {
        parent::__construct($nodeName);

        $this->ruleSet = is_null($ruleSet) ? new RuleSet() : $ruleSet;
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return mixed
     */
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        foreach ($element->childNodes as $domNode) {
            if ($domNode instanceof \DomElement) {
                $rule = $this->ruleSet->get($domNode->tagName);
                $rule->setProperty($node, $domNode);
            }
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
        $element = $document->createElement($this->getNodeName());
        foreach ($this->ruleSet->getRules() as $rule) {
            $element->appendChild($rule->createElement($document, $node));
        }

        return $element;
    }
}
