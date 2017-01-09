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

use DOMDocument;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Parser\MissingFieldsException;
use FeedIo\Parser\UnsupportedFormatException;
use Psr\Log\LoggerInterface;

/**
 * Parses a DOM document if its format matches the parser's standard
 *
 * Depends on :
 *  - FeedIo\StandardAbstract
 *  - Psr\Log\LoggerInterface
 *
 */
class Parser
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var array[FilterInterface]
     */
    protected $filters = array();

    /**
     * @var StandardAbstract
     */
    protected $standard;

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
     * @return StandardAbstract
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * @param $tagName
     * @return bool
     */
    public function isItem($tagName)
    {
        return (strtolower($this->standard->getItemNodeName()) === strtolower($tagName));
    }

    /**
     * @param  FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * Reset filters
     * @return $this
     */
    public function resetFilters()
    {
        $this->filters = [];

        return $this;
    }

    /**
     * @param  DOMDocument                       $document
     * @param  FeedInterface                     $feed
     * @return \FeedIo\FeedInterface
     * @throws Parser\MissingFieldsException
     * @throws Parser\UnsupportedFormatException
     */
    public function parse(DOMDocument $document, FeedInterface $feed)
    {
        if (!$this->standard->canHandle($document)) {
            throw new UnsupportedFormatException('this is not a supported format');
        }

        $this->checkBodyStructure($document, $this->standard->getMandatoryFields());
        $element = $this->standard->getMainElement($document);

        $this->parseNode($feed, $element, $this->standard->getFeedRuleSet());

        return $feed;
    }

    /**
     * @param  DOMDocument            $document
     * @param  array                  $mandatoryFields
     * @return $this
     * @throws MissingFieldsException
     */
    public function checkBodyStructure(DOMDocument $document, array $mandatoryFields)
    {
        $errors = array();

        $element = $document->documentElement;
        foreach ($mandatoryFields as $field) {
            $list = $element->getElementsByTagName($field);
            if (0 === $list->length) {
                $errors[] = $field;
            }
        }

        if (!empty($errors)) {
            $message = "missing mandatory field(s) : ".implode(',', $errors);
            $this->logger->warning($message);
            throw new MissingFieldsException($message);
        }

        return $this;
    }

    /**
     * @param  NodeInterface $item
     * @param  \DOMElement   $element
     * @param  RuleSet       $ruleSet
     * @return NodeInterface
     */
    public function parseNode(NodeInterface $item, \DOMElement $element, RuleSet $ruleSet)
    {
        foreach ($element->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                $this->handleNode($item, $node, $ruleSet);
            }
        }

        return $item;
    }

    /**
     * @param NodeInterface $item
     * @param \DOMElement $node
     * @param RuleSet $ruleSet
     * @return $this
     */
    protected function handleNode(NodeInterface $item, \DOMElement $node, RuleSet $ruleSet)
    {
        if ($this->isItem($node->tagName) && $item instanceof FeedInterface) {
            $newItem = $this->parseNode($item->newItem(), $node, $this->standard->getItemRuleSet());
            $this->addValidItem($item, $newItem);
        } else {
            $rule = $ruleSet->get($node->tagName);
            $rule->setProperty($item, $node);
        }

        return $this;
    }

    /**
     * @param  FeedInterface $feed
     * @param  NodeInterface $item
     * @return $this
     */
    public function addValidItem(FeedInterface $feed, NodeInterface $item)
    {
        if ($item instanceof ItemInterface && $this->isValid($item)) {
            $feed->add($item);
        }

        return $this;
    }

    /**
     * @param  ItemInterface $item
     * @return bool
     */
    public function isValid(ItemInterface $item)
    {
        foreach ($this->filters as $filter) {
            if (!$filter->isValid($item)) {
                return false;
            }
        }

        return true;
    }
}
