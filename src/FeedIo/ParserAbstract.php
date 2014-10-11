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
use FeedIo\Feed\NodeInterface;
use FeedIo\Parser\Date;
use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\MissingFieldsException;
use FeedIo\Parser\RuleAbstract;
use FeedIo\Parser\RuleSet;
use FeedIo\Parser\UnsupportedFormatException;
use Psr\Log\LoggerInterface;

abstract class ParserAbstract
{
    const FEED_TITLE = 'title';

    const FEED_DESCRIPTION = 'description';

    const FEED_URL = 'link';

    const FEED_LAST_MODIFIED = 'updated';

    const FEED_ITEM = 'item';

    const FEED_PUBLIC_ID = 'publicId';

    const ITEM_TITLE = 'title';

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
     * @var \FeedIo\Parser\Date
     */
    protected $date;

    /**
     * @var RuleSet
     */
    protected $ruleSet;

    /**
     * @param Date $date
     * @param LoggerInterface $logger
     */
    public function __construct(Date $date, LoggerInterface $logger)
    {
        $this->date = $date;
        $this->logger = $logger;
        $this->ruleSet = new RuleSet();
    }

    /**
     * @param RuleAbstract $rule
     * @return $this
     */
    public function addRule(RuleAbstract $rule)
    {
        $this->ruleSet->add($rule);

        return $this;
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

        $this->checkBodyStructure($document);

        $element = $this->getMainElement($document);

        return $this->parseRootNode($element, $feed);
    }

    /**
     * @param DOMDocument $document
     * @return $this
     * @throws Parser\MissingFieldsException
     */
    public function checkBodyStructure(DOMDocument $document)
    {
        $errors = array();

        $element = $document->documentElement;
        foreach ($this->mandatoryFields as $field) {
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
     * @param \DOMElement $element
     * @param FeedInterface $feed
     * @return FeedInterface
     */
    public function parseRootNode(\DOMElement $element, FeedInterface $feed)
    {
        foreach ($element->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                $rule = $this->ruleSet->get(strtolower($node->tagName));
                $rule->set($feed, $node);
            }
        }

        return $feed;
    }

    /**
     * @param \DOMElement $element
     * @param FeedInterface $feed
     * @return ItemInterface
     */
    public function parseItemNode(\DOMElement $element, FeedInterface $feed)
    {
        $item = $feed->newItem();
        foreach ($element->childNodes as $node) {
            if ($node instanceof \DOMElement) {
                switch (strtolower($node->tagName)) {
                    case static::ITEM_TITLE:
                        $item->setTitle($node->nodeValue);
                        break;
                    default:
                        $item->getOptionalFields()->set($node->nodeName, $node->nodeValue);
                        break;
                }
            }
        }

        return $item;
    }

    /**
     * @param NodeInterface $node
     * @param $value
     * @return NodeInterface
     * @throws \InvalidArgumentException
     */
    public function setLastModifiedSince(NodeInterface $node, $value)
    {
        try {
            $date = $this->date->convertToDateTime($value);
            if ($date instanceof \DateTime) {
                $node->setLastModified($date);
            }
            return $node;
        } catch (\InvalidArgumentException $e) {
            $this->logger->warning($e->getMessage());
            throw $e;
        }
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

}
