<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DOMDocument;
use DOMElement;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;
use FeedIo\RuleSet;

class Structure extends RuleAbstract
{
    public const NODE_NAME = 'structure';

    protected RuleSet $ruleSet;

    public function __construct(string $nodeName = null, RuleSet $ruleSet = null)
    {
        parent::__construct($nodeName);

        $this->ruleSet = $ruleSet ?? new RuleSet();
    }

    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        foreach ($element->childNodes as $domNode) {
            if ($domNode instanceof DomElement) {
                $rule = $this->ruleSet->get($domNode->tagName);
                $rule->setProperty($node, $domNode);
            }
        }
    }

    protected function hasValue(NodeInterface $node): bool
    {
        return true;
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        $element = $document->createElement($this->getNodeName());

        /** @var RuleAbstract $rule */
        foreach ($this->ruleSet->getRules() as $rule) {
            $rule->apply($document, $element, $node);
        }

        $rootElement->appendChild($element);
    }
}
