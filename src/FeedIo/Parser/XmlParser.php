<?php

declare(strict_types=1);

namespace FeedIo\Parser;

use DOMElement;
use FeedIo\FeedIoException;
use FeedIo\RuleSet;
use FeedIo\FeedInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\ParserAbstract;
use FeedIo\Reader\Document;
use FeedIo\Standard\XmlAbstract;

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
    public function isItem(string $tagName): bool
    {
        if ($this->standard instanceof XmlAbstract) {
            return (strtolower($this->standard->getItemNodeName()) === strtolower($tagName));
        }

        return false;
    }

    public function parseContent(Document $document, FeedInterface $feed): FeedInterface
    {
        if ($this->standard instanceof XmlAbstract) {
            $element = $this->standard->getMainElement($document->getDOMDocument());
            $this->parseNode($feed, $element, $this->standard->getFeedRuleSet());
        }
        return $feed;
    }

    public function checkBodyStructure(Document $document, iterable $mandatoryFields): bool
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

    public function parseNode(NodeInterface $item, DOMElement $element, RuleSet $ruleSet): NodeInterface
    {
        foreach ($element->childNodes as $node) {
            if ($node instanceof DOMElement) {
                $this->handleNode($item, $node, $ruleSet);
            }
        }

        return $item;
    }

    protected function handleNode(NodeInterface $item, DOMElement $node, RuleSet $ruleSet): void
    {
        if ($this->isItem($node->tagName) && $item instanceof FeedInterface) {
            $linkItem = $item->getLink();
            $newItem = $this->parseNode($item->newItem()->setLink($linkItem), $node, $this->getItemRuleSet());
            $this->addValidItem($item, $newItem);
        } else {
            $rule = $ruleSet->get($node->tagName);
            $rule->setProperty($item, $node);
        }
    }

    protected function getItemRuleSet(): RuleSet
    {
        if ($this->standard instanceof XmlAbstract) {
            return $this->standard->getItemRuleSet();
        }
        throw new FeedIoException('Not an XML Standard');
    }
}
