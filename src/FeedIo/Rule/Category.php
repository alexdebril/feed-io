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

use FeedIo\Feed\Node\CategoryInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Category extends RuleAbstract
{
    const NODE_NAME = 'category';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     * @return mixed
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        $category = $node->newCategory();
        $category->setScheme($this->getAttributeValue($element, 'domain'))
        ->setLabel($element->nodeValue)
        ->setTerm($element->nodeValue);
        $node->addCategory($category);
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return !! $node->getCategories();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        foreach ($node->getCategories() as $category) {
            $rootElement->appendChild($this->createCategoryElement($document, $category));
        }
    }

    /**
     * @param  \DomDocument   $document
     * @param  CategoryInterface $category
     * @return \DomElement
     */
    public function createCategoryElement(\DomDocument $document, CategoryInterface $category) : \DOMElement
    {
        $element = $document->createElement(
            $this->getNodeName(),
            is_null($category->getTerm()) ? $category->getLabel():$category->getTerm()
            );
        if (!! $category->getScheme()) {
            $element->setAttribute('domain', $category->getScheme());
        }

        return $element;
    }
}
