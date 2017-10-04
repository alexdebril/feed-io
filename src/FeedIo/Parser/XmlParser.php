<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;

use FeedIo\Parser;
use FeedIo\RuleSet;
use FeedIo\FeedInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\ParserAbstract;
use FeedIo\Reader\Document;
use FeedIo\Parser\MissingFieldsException;
use FeedIo\Parser\UnsupportedFormatException;

/**
 * Parses a DOM document if its format matches the parser's standard
 *
 * Depends on :
 *  - FeedIo\StandardAbstract
 *  - Psr\Log\LoggerInterface
 *
 */
class XmlParser extends ParserAbstract
{

    /**
     * @param $tagName
     * @return bool
     */
    public function isItem(string $tagName) : bool
    {
        return (strtolower($this->standard->getItemNodeName()) === strtolower($tagName));
    }

    /**
     * @param  Document                       $document
     * @param  FeedInterface                  $feed
     * @return \FeedIo\FeedInterface
     * @throws Parser\MissingFieldsException
     * @throws Parser\UnsupportedFormatException
     */
    public function parseContent(Document $document, FeedInterface $feed) : FeedInterface
    {
        $element = $this->standard->getMainElement($document->getDOMDocument());

        $this->parseNode($feed, $element, $this->standard->getFeedRuleSet());

        return $feed;
    }

    /**
     * @param Document $document
     * @param iterable $mandatoryFields
     * @throws MissingFieldsException
     * @return bool
     */
    public function checkBodyStructure(Document $document, iterable $mandatoryFields) : bool
    {
        $errors = array();

        $element = $document->getDOMDocument()->documentElement;
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

        return true;
    }

    /**
     * @param  NodeInterface $item
     * @param  \DOMElement   $element
     * @param  RuleSet       $ruleSet
     * @return NodeInterface
     */
    public function parseNode(NodeInterface $item, \DOMElement $element, RuleSet $ruleSet) : NodeInterface
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
     */
    protected function handleNode(NodeInterface $item, \DOMElement $node, RuleSet $ruleSet) : void
    {
        if ($this->isItem($node->tagName) && $item instanceof FeedInterface) {
            $newItem = $this->parseNode($item->newItem(), $node, $this->standard->getItemRuleSet());
            $this->addValidItem($item, $newItem);
        } else {
            $rule = $ruleSet->get($node->tagName);
            $rule->setProperty($item, $node);
        }
    }
}
