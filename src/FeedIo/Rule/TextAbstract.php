<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DOMDocument;
use DOMElement;
use FeedIo\Feed\NodeInterface;
use FeedIo\RuleAbstract;

abstract class TextAbstract extends RuleAbstract
{
    protected function getFormattedContent(\DOMElement $element): string
    {
        $string = '';
        foreach ($element->childNodes as $childNode) {
            if ($childNode->nodeType == XML_CDATA_SECTION_NODE) {
                $string .= $childNode->textContent;
            } else {
                $string .= $element->ownerDocument->saveXML($childNode);
            }
        }
        return $string;
    }

    protected function processString(string $input, NodeInterface $node): string
    {
        return preg_replace(
            ['/href="\/(\w+)/', '/src="\/(\w+)/'],
            ['href="'. $node->getHost() . '/${1}', 'src="' . $node->getHost() . '/${1}'],
            htmlspecialchars_decode($input)
        );
    }

    protected function getProcessedContent(DOMElement $element, NodeInterface $node): string
    {
        return $this->processString(
            $this->getFormattedContent($element),
            $node
        );
    }

    protected function generateElement(DOMDocument $document, string $content): DOMElement
    {
        $processedContent = htmlspecialchars($content);
        return $document->createElement($this->getNodeName(), $processedContent);
    }

    protected function generateTypedElement(DomDocument $document, string $content): DOMElement
    {
        $element = $this->generateElement($document, $content);
        if ($content !== $element->textContent) {
            $element->setAttribute('type', 'html');
        }

        return $element;
    }
}
