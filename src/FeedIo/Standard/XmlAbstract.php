<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Standard;


use FeedIo\Formatter\XmlFormatter;
use FeedIo\StandardAbstract;
use FeedIo\RuleSet;
use FeedIo\Rule\ModifiedSince;
use FeedIo\Rule\Title;

abstract class XmlAbstract extends StandardAbstract
{

    /**
     * Name of the node containing all the feed's items
     */
    const ITEM_NODE = 'item';

    /**
     * This is for XML Standards
     */
    const SYNTAX_FORMAT = 'Xml';

    /**
     * RuleSet used to parse the feed's main node
     * @var \FeedIo\RuleSet
     */
    protected $feedRuleSet;

    /**
     * @var \FeedIo\RuleSet
     */
    protected $itemRuleSet;

    /**
     * Formats the document according to the standard's specification
     * @param  \DOMDocument $document
     * @return mixed
     */
    abstract public function format(\DOMDocument $document);

    /**
     * @param  \DOMDocument $document
     * @return \DomElement
     */
    abstract public function getMainElement(\DOMDocument $document);

    /**
     * Builds and returns a rule set to parse the root node
     * @return \FeedIo\RuleSet
     */
    abstract public function buildFeedRuleSet();

    /**
     * Builds and returns a rule set to parse an item
     * @return \FeedIo\RuleSet
     */
    abstract public function buildItemRuleSet();

    /**
     * @return string
     */
    public function getItemNodeName()
    {
        return static::ITEM_NODE;
    }

    /**
     * @return XmlFormatter
     */
    public function getFormatter()
    {
        return new XmlFormatter($this);
    }

    /**
     * Returns the RuleSet used to parse the feed's main node
     * @return \FeedIo\RuleSet
     */
    public function getFeedRuleSet()
    {
        if (is_null($this->feedRuleSet)) {
            $this->feedRuleSet = $this->buildFeedRuleSet();
        }

        return $this->feedRuleSet;
    }

    /**
     * @return \FeedIo\RuleSet
     */
    public function getItemRuleSet()
    {
        if (is_null($this->itemRuleSet)) {
            $this->itemRuleSet = $this->buildItemRuleSet();
        }

        return $this->itemRuleSet;
    }

    /**
     * @param  string        $tagName
     * @return ModifiedSince
     */
    public function getModifiedSinceRule($tagName)
    {
        $rule = new ModifiedSince($tagName);
        $rule->setDefaultFormat($this->getDefaultDateFormat());
        $rule->setDateTimeBuilder($this->dateTimeBuilder);

        return $rule;
    }

    /**
     * @return RuleSet
     */
    protected function buildBaseRuleSet()
    {
        $ruleSet = $ruleSet = new RuleSet();
        $ruleSet->add(new Title());

        return $ruleSet;
    }
}
