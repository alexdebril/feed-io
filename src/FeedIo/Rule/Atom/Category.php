<?php

declare(strict_types=1);

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\Node\CategoryInterface;
use FeedIo\Feed\NodeInterface;

class Category extends \FeedIo\Rule\Category
{
    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element): void
    {
        $category = $node->newCategory();
        $category->setScheme($this->getAttributeValue($element, 'scheme'))
        ->setLabel($this->getAttributeValue($element, 'label'))
        ->setTerm($this->getAttributeValue($element, 'term'));

        $node->addCategory($category);
    }

    /**
     * @param  \DomDocument   $document
     * @param  CategoryInterface $category
     * @return \DomElement
     */
    public function createCategoryElement(\DomDocument $document, CategoryInterface $category): \DOMElement
    {
        $element = $document->createElement($this->getNodeName());
        $this->setNonEmptyAttribute($element, 'scheme', $category->getScheme());
        $this->setNonEmptyAttribute($element, 'term', $category->getTerm());
        $this->setNonEmptyAttribute($element, 'label', $category->getLabel());

        return $element;
    }
}
