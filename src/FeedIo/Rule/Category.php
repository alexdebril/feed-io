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
    public function setProperty(NodeInterface $node, \DOMElement $element)
    {
        $category = $node->newCategory();
        $category->setScheme($this->getAttributeValue($element, 'domain'))
        ->setLabel($element->nodeValue)
        ->setTerm($element->nodeValue);
        $node->addCategory($category);        

        return $this;
    }

    /**
     * creates the accurate DomElement content according to the $item's property
     *
     * @param  \DomDocument  $document
     * @param  NodeInterface $node
     * @return \DomElement|null
     */
    public function createElement(\DomDocument $document, NodeInterface $node)
    {
        if ( ! is_null($node->getCategories()) ) {
            foreach( $node->getCategories() as $category ) {
                return $this->createCategoryElement($document, $category);
            }
        }
        
        return;
    }
    
    /**
     * @param  \DomDocument   $document
     * @param  CategoryInterface $category
     * @return \DomElement
     */
    public function createCategoryElement(\DomDocument $document, CategoryInterface $category)
    {
        $element = $document->createElement(
            $this->getNodeName(),
            is_null($category->getTerm()) ? $category->getLabel():$category->getTerm()
            );
        $element->setAttribute('domain', $category->getScheme());

        return $element;
    }
}
