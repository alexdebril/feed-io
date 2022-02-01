<?php

declare(strict_types=1);

namespace FeedIo\Formatter;

use DOMDocument;
use DOMElement;
use FeedIo\Feed\ElementsAwareInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Feed\StyleSheet;
use FeedIo\FeedInterface;
use FeedIo\Rule\OptionalField;
use FeedIo\RuleSet;
use FeedIo\Standard\XmlAbstract;
use FeedIo\FormatterInterface;

/**
 * Turns a FeedInterface instance into a XML document.
 *
 * Depends on :
 *  - FeedIo\StandardAbstract
 *
 */
class XmlFormatter implements FormatterInterface
{
    public function __construct(
        protected XmlAbstract $standard
    ) {
    }

    public function setHeaders(DOMDocument $document, FeedInterface $feed): XmlFormatter
    {
        $rules = $this->standard->getFeedRuleSet();
        $mainElement = $this->standard->getMainElement($document);
        $this->buildElements($rules, $document, $mainElement, $feed);

        return $this;
    }

    public function addItem(DOMDocument $document, NodeInterface $node): XmlFormatter
    {
        $domItem = $document->createElement($this->standard->getItemNodeName());
        $rules = $this->standard->getItemRuleSet();
        $this->buildElements($rules, $document, $domItem, $node);

        $this->standard->getMainElement($document)->appendChild($domItem);

        return $this;
    }

    public function buildElements(RuleSet $ruleSet, DOMDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        $rules = $this->getAllRules($ruleSet, $node);

        foreach ($rules as $rule) {
            $rule->apply($document, $rootElement, $node);
        }
    }

    public function getAllRules(RuleSet $ruleSet, NodeInterface $node): iterable
    {
        $rules = $ruleSet->getRules();
        if ($node instanceof ElementsAwareInterface) {
            $optionalFields = $node->listElements();
            foreach ($optionalFields as $optionalField) {
                $rules[$optionalField] = new OptionalField($optionalField);
            }
        }

        return $rules;
    }

    public function getEmptyDocument(): DOMDocument
    {
        return new DOMDocument('1.0', 'utf-8');
    }

    public function getDocument(): DOMDocument
    {
        $document = $this->getEmptyDocument();

        return $this->standard->format($document);
    }

    /**
     * @param  FeedInterface $feed
     * @return string
     */
    public function toString(FeedInterface $feed): string
    {
        $document = $this->toDom($feed);

        return $document->saveXML();
    }

    public function toDom(FeedInterface $feed): DOMDocument
    {
        $document = $this->getDocument();

        $this->setHeaders($document, $feed);
        $this->setItems($document, $feed);
        $this->setNS($document, $feed);
        $this->setStyleSheet($document, $feed);

        return $document;
    }

    public function setNS(DOMDocument $document, FeedInterface $feed)
    {
        $firstChild = $document->firstChild;
        if ($firstChild instanceof DOMElement) {
            foreach ($feed->getNS() as $namespace => $dtd) {
                $firstChild->setAttributeNS(
                    'http://www.w3.org/2000/xmlns/', // xmlns namespace URI
                    'xmlns:'.$namespace,
                    $dtd
                );
            }
        }
    }

    public function setItems(DOMDocument $document, FeedInterface $feed): XmlFormatter
    {
        foreach ($feed as $item) {
            $this->addItem($document, $item);
        }

        return $this;
    }

    public function setStyleSheet(DOMDocument $document, FeedInterface $feed): XmlFormatter
    {
        $styleSheet = $feed->getStyleSheet();
        if ($styleSheet instanceof StyleSheet) {
            $attributes = sprintf('type="%s" href="%s"', $styleSheet->getType(), $styleSheet->getHref());
            $xsl = $document->createProcessingInstruction('xml-stylesheet', $attributes);
            $document->insertBefore($xsl, $document->firstChild);
        }

        return $this;
    }
}
