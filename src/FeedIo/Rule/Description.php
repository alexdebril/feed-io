<?php declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 26/10/14
 * Time: 00:26
 */
namespace FeedIo\Rule;

use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

class Description extends RuleAbstract
{
    const NODE_NAME = 'description';

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        $string = '';
        if ($element->firstChild && $element->firstChild->nodeType == XML_CDATA_SECTION_NODE) {
            $string = $element->firstChild->textContent;
        } else {
            foreach ($element->childNodes as $childNode) {
                $string .= $element->ownerDocument->saveXML($childNode);
            }
        }

        $node->setDescription(htmlspecialchars_decode($string));
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return !! $node->getDescription();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        $description = htmlspecialchars($node->getDescription());
        $element = $document->createElement($this->getNodeName(), $description);
        if ($description !== $node->getDescription() && $this->getNodeName() != 'description') {
            $element->setAttribute('type', 'html');
        }

        $rootElement->appendChild($element);
    }
}
