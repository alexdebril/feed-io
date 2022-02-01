<?php

declare(strict_types=1);

namespace FeedIo\Standard;

use DOMDocument;
use DOMElement;
use FeedIo\Formatter\XmlFormatter;
use FeedIo\FormatterInterface;
use FeedIo\StandardAbstract;
use FeedIo\RuleSet;
use FeedIo\Rule\ModifiedSince;
use FeedIo\Rule\Title;

abstract class XmlAbstract extends StandardAbstract
{
    /**
     * Name of the node containing all the feed's items
     */
    public const ITEM_NODE = 'item';

    /**
     * This is for XML Standards
     */
    public const SYNTAX_FORMAT = 'Xml';

    protected ?RuleSet $feedRuleSet = null;

    protected ?RuleSet $itemRuleSet = null;

    abstract public function format(DOMDocument $document): DOMDocument;

    abstract public function getMainElement(DOMDocument $document): DOMElement;

    abstract public function buildFeedRuleSet(): RuleSet;

    abstract public function buildItemRuleSet(): RuleSet;

    public function getItemNodeName(): string
    {
        return static::ITEM_NODE;
    }

    public function getFormatter(): FormatterInterface
    {
        return new XmlFormatter($this);
    }

    public function getFeedRuleSet(): RuleSet
    {
        if (is_null($this->feedRuleSet)) {
            $this->feedRuleSet = $this->buildFeedRuleSet();
        }

        return $this->feedRuleSet;
    }

    public function getItemRuleSet(): RuleSet
    {
        if (is_null($this->itemRuleSet)) {
            $this->itemRuleSet = $this->buildItemRuleSet();
        }

        return $this->itemRuleSet;
    }

    public function getModifiedSinceRule(string $tagName): ModifiedSince
    {
        $rule = new ModifiedSince($tagName);
        $rule->setDefaultFormat($this->getDefaultDateFormat());
        $rule->setDateTimeBuilder($this->dateTimeBuilder);

        return $rule;
    }

    protected function buildBaseRuleSet(): RuleSet
    {
        $ruleSet = $ruleSet = new RuleSet();
        $ruleSet->add(new Title());

        return $ruleSet;
    }
}
