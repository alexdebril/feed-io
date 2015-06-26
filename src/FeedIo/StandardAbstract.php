<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 05/12/14
 * Time: 22:46
 */
namespace FeedIo;

use FeedIo\Rule\DateTimeBuilder;
use FeedIo\Rule\ModifiedSince;
use FeedIo\Rule\Title;

abstract class StandardAbstract
{
    /**
     * Name of the node containing all the feed's items
     */
    const ITEM_NODE = 'item';

    /**
     * DateTime default format
     */
    const DATETIME_FORMAT = \DateTime::RFC2822;

    /**
     * @var array
     */
    protected $mandatoryFields = array();

    /**
     * @var \FeedIo\Rule\DateTimeBuilder
     */
    protected $dateTimeBuilder;

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
     * @param \FeedIo\Rule\DateTimeBuilder $dateTimeBuilder
     */
    public function __construct(DateTimeBuilder $dateTimeBuilder)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

    /**
     * Formats the document according to the standard's specification
     * @param  \DOMDocument $document
     * @return mixed
     */
    abstract public function format(\DOMDocument $document);

    /**
     * Tells if the parser can handle the feed or not
     * @param  \DOMDocument $document
     * @return mixed
     */
    abstract public function canHandle(\DOMDocument $document);

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
     * @return string
     */
    public function getDefaultDateFormat()
    {
        return static::DATETIME_FORMAT;
    }

    /**
     * @return array
     */
    public function getMandatoryFields()
    {
        return $this->mandatoryFields;
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
