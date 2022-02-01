<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DomDocument;
use DOMElement;
use FeedIo\Feed\Node\CategoryInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Category extends RuleAbstract
{
    public const NODE_NAME = 'category';

    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        $category = $node->newCategory();
        $category->setScheme($this->getAttributeValue($element, 'domain'))
        ->setLabel($element->nodeValue)
        ->setTerm($element->nodeValue);
        $node->addCategory($category);
    }

    protected function hasValue(NodeInterface $node): bool
    {
        return !! $node->getCategories();
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        foreach ($node->getCategories() as $category) {
            $rootElement->appendChild($this->createCategoryElement($document, $category));
        }
    }

    public function createCategoryElement(DomDocument $document, CategoryInterface $category): DOMElement
    {
        $element = $document->createElement(
            $this->getNodeName(),
            is_null($category->getTerm()) ? $category->getLabel() : $category->getTerm()
        );
        if (!! $category->getScheme()) {
            $element->setAttribute('domain', $category->getScheme());
        }

        return $element;
    }
}
