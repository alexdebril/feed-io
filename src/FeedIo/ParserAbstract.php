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

use \DOMDocument;
use FeedIo\Feed\Item;
use FeedIo\Parser\DateTimeBuilder;
use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\MissingFieldsException;
use FeedIo\Parser\RuleSet;
use FeedIo\Parser\Rule\ModifiedSince;
use FeedIo\Parser\UnsupportedFormatException;
use Psr\Log\LoggerInterface;

abstract class ParserAbstract
{
    const ITEM_NODE = 'item';

    /**
     * List of mandatory fields
     *
     * @var array[string]
     */
    protected $mandatoryFields = array();

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var array[FilterInterface]
     */
    protected $filters = array();

    /**
     * @var \FeedIo\Parser\DateTimeBuilder
     */
    protected $dateTimeBuilder;

    /**
     * RuleSet used to parse the feed's main node
     * @var \FeedIo\Parser\RuleSet
     */
    protected $feedRuleSet;

    /**
     * @var \FeedIo\Parser\RuleSet
     */
    protected $itemRuleSet;

    /**
     * @param DateTimeBuilder $dateTimeBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(DateTimeBuilder $dateTimeBuilder, LoggerInterface $logger)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
        $this->logger = $logger;
    }

    /**
     * Returns the RuleSet used to parse the feed's main node
     * @return \FeedIo\Parser\RuleSet
     */
    public function getFeedRuleSet()
    {
        if ( is_null($this->feedRuleSet) ) {
            $this->feedRuleSet = $this->buildFeedRuleSet();
        }

        return $this->feedRuleSet;
    }

    /**
     * @return \FeedIo\Parser\RuleSet
     */
    public function getItemRuleSet()
    {
        if ( is_null($this->itemRuleSet) ) {
            $this->itemRuleSet = $this->buildItemRuleSet();
        }

        return $this->itemRuleSet;
    }

    /**
     * @param $tagName
     * @return bool
     */
    public function isItem($tagName)
    {
        return ( strtolower(static::ITEM_NODE) === strtolower($tagName) );
    }

    /**
     * @param FilterInterface $filter
     * @return $this
     */
    public function addFilter(FilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @param DOMDocument $document
     * @param FeedInterface $feed
     * @return FeedInterface
     * @throws Parser\MissingFieldsException
     * @throws Parser\UnsupportedFormatException
     */
    public function parse(DOMDocument $document, FeedInterface $feed)
    {
        if (!$this->canHandle($document)) {
            throw new UnsupportedFormatException('this is not a supported format');
        }

        $this->checkBodyStructure($document, $this->mandatoryFields);
        $element = $this->getMainElement($document);

        return $this->parseNode($feed, $element, $this->getFeedRuleSet());
    }

    /**
     * @param DOMDocument $document
     * @param array $mandatoryFields
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
            $message = "missing mandatory field(s) : " . implode(',', $errors);
            $this->logger->warning($message);
            throw new MissingFieldsException($message);
        }

        return $this;
    }

    /**
     * @param ItemInterface $item
     * @param \DOMElement $element
     * @param RuleSet $ruleSet
     * @return ItemInterface
     */
    public function parseNode(ItemInterface $item, \DOMElement $element, RuleSet $ruleSet)
    {
        foreach ($element->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                if ( $this->isItem($node->tagName) && $item instanceof FeedInterface) {
                    $newItem = $this->parseNode($item->newItem(), $node, $this->getItemRuleSet());
                    $this->addValidItem($item, $newItem);
                } else {
                    $rule = $ruleSet->get($node->tagName);
                    $rule->set($item, $node);
                }
            }
        }

        return $item;
    }

    /**
     * @param $tagName
     * @return ModifiedSince
     */
    public function getModifiedSinceRule($tagName)
    {
        $rule = new ModifiedSince($tagName);
        $rule->setDateTimeBuilder($this->dateTimeBuilder);

        return $rule;
    }

    /**
     * @param FeedInterface $feed
     * @param ItemInterface $item
     * @return $this
     */
    public function addValidItem(FeedInterface $feed, ItemInterface $item)
    {
        if ($this->isValid($item)) {
            $feed->add($item);
        }

        return $this;
    }

    /**
     * @param ItemInterface $item
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

    /**
     * Tells if the parser can handle the feed or not
     * @param \DOMDocument $document
     * @return mixed
     */
    abstract public function canHandle(\DOMDocument $document);

    /**
     * @param DOMDocument $document
     * @return \DomElement
     */
    abstract public function getMainElement(\DOMDocument $document);

    /**
     * Builds and returns a rule set to parse the root node
     * @return $this
     */
    abstract public function buildFeedRuleSet();

    /**
     * Builds and returns a rule set to parse an item
     * @return $this
     */
    abstract public function buildItemRuleSet();

}
