<?php declare(strict_types=1);
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
    public function __construct(string $nodeName = null, RuleSet $ruleSet = null)
    {
        parent::__construct($nodeName);

        $this->ruleSet = is_null($ruleSet) ? new RuleSet() : $ruleSet;
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return mixed
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        foreach ($element->childNodes as $domNode) {
            if ($domNode instanceof \DomElement) {
                $rule = $this->ruleSet->get($domNode->tagName);
                $rule->setProperty($node, $domNode);
            }
        }
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        $element = $document->createElement($this->getNodeName());

        /** @var RuleAbstract $rule */
        foreach ($this->ruleSet->getRules() as $rule) {
            $rule->apply($document, $element, $node);
        }

        $rootElement->appendChild($element);
    }
}
