<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use FeedIo\Feed\NodeInterface;
use FeedIo\Rule\OptionalField;
use Psr\Log\LoggerInterface;

/**
 * Turns a FeedInterface instance into a XML document.
 *
 * Depends on :
 *  - FeedIo\StandardAbstract
 *  - Psr\Log\LoggerInterface
 *
 */
class Formatter
{

    /**
     * @var StandardAbstract
     */
    protected $standard;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param StandardAbstract $standard
     * @param LoggerInterface  $logger
     */
    public function __construct(StandardAbstract $standard, LoggerInterface $logger)
    {
        $this->standard = $standard;
        $this->logger = $logger;
    }

    /**
     * @param  \DOMDocument  $document
     * @param  FeedInterface $feed
     * @return $this
     */
    public function setHeaders(\DOMDocument $document, FeedInterface $feed)
    {
        $rules = $this->standard->getFeedRuleSet();
        $elements = $this->buildElements($rules, $document, $feed);
        foreach ($elements as $element) {
            $this->standard->getMainElement($document)->appendChild($element);
        }

        return $this;
    }

    /**
     * @param  \DOMDocument  $document
     * @param  NodeInterface $node
     * @return $this
     */
    public function addItem(\DOMDocument $document, NodeInterface $node)
    {
        $domItem = $document->createElement($this->standard->getItemNodeName());
        $rules = $this->standard->getItemRuleSet();
        $elements = $this->buildElements($rules, $document, $node);

        foreach ($elements as $element) {
            $domItem->appendChild($element);
        }

        $this->standard->getMainElement($document)->appendChild($domItem);

        return $this;
    }

    /**
     * @param  RuleSet       $ruleSet
     * @param  \DOMDocument  $document
     * @param  NodeInterface $node
     * @return array
     */
    public function buildElements(RuleSet $ruleSet, \DOMDocument $document, NodeInterface $node)
    {
        $rules = $this->getAllRules($ruleSet, $node);
        $elements = array();
        foreach ($rules as $rule) {
            $elements[] = $rule->createElement($document, $node);
        }

        return array_filter($elements);
    }

    /**
     * @param  RuleSet              $ruleSet
     * @param  NodeInterface        $node
     * @return array|\ArrayIterator
     */
    public function getAllRules(RuleSet $ruleSet, NodeInterface $node)
    {
        $rules = $ruleSet->getRules();
        $optionalFields = $node->listElements();
        foreach ($optionalFields as $optionalField) {
            $rules[] = new OptionalField($optionalField);
        }

        return $rules;
    }

    /**
     * @return \DOMDocument
     */
    public function getEmptyDocument()
    {
        return new \DOMDocument('1.0', 'utf-8');
    }

    /**
     * @return \DOMDocument
     */
    public function getDocument()
    {
        $document = $this->getEmptyDocument();

        return $this->standard->format($document);
    }

    /**
     * @param  FeedInterface $feed
     * @return string
     */
    public function toString(FeedInterface $feed)
    {
        $document = $this->toDom($feed);

        return $document->saveXML();
    }

    /**
     * @param  FeedInterface $feed
     * @return \DomDocument
     */
    public function toDom(FeedInterface $feed)
    {
        $document = $this->getDocument();

        $this->setHeaders($document, $feed);
        $this->setItems($document, $feed);

        return $document;
    }

    /**
     * @param  \DOMDocument  $document
     * @param  FeedInterface $feed
     * @return $this
     */
    public function setItems(\DOMDocument $document, FeedInterface $feed)
    {
        foreach ($feed as $item) {
            $this->addItem($document, $item);
        }

        return $this;
    }
}
