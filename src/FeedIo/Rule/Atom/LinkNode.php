<?php

declare(strict_types=1);

namespace FeedIo\Rule\Atom;

use DomDocument;
use DOMElement;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;
use FeedIo\RuleSet;

class LinkNode extends RuleAbstract
{
    public const NODE_NAME = 'link';

    protected RuleSet $ruleSet;

    public function __construct(string $nodeName = null)
    {
        parent::__construct($nodeName);
        $mediaRule = new Media();
        $mediaRule->setUrlAttributeName('href');
        $this->ruleSet = new RuleSet(new Link('related'));
        $this->ruleSet->add($mediaRule, ['media', 'enclosure']);
    }

    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        if ($element->hasAttribute('rel')) {
            $this->ruleSet->get($element->getAttribute('rel'))->setProperty($node, $element);
        } else {
            $this->ruleSet->getDefault()->setProperty($node, $element);
        }
    }

    protected function hasValue(NodeInterface $node): bool
    {
        return true;
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        if ($node instanceof ItemInterface && $node->hasMedia()) {
            $this->ruleSet->get('media')->apply($document, $rootElement, $node);
        }

        $this->ruleSet->getDefault()->apply($document, $rootElement, $node);
    }
}
