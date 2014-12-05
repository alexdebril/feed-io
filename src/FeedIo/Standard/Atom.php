<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 22/11/14
 * Time: 11:45
 */

namespace FeedIo\Standard;


use DOMDocument;
use FeedIo\Rule\Atom\Link;
use FeedIo\Rule\Description;
use FeedIo\Rule\PublicId;
use FeedIo\StandardAbstract;

class Atom extends StandardAbstract
{
    /**
     * Atom document must have a <feed> root node
     */
    const ROOT_NODE_TAGNAME = 'feed';

    const ITEM_NODE = 'entry';

    /**
     * Tells if the parser can handle the feed or not
     * @param \DOMDocument $document
     * @return mixed
     */
    public function canHandle(\DOMDocument $document)
    {
        return self::ROOT_NODE_TAGNAME === $document->documentElement->tagName;
    }

    /**
     * @param DOMDocument $document
     * @return \DomElement
     */
    public function getMainElement(\DOMDocument $document)
    {
        return $document->documentElement;
    }

    /**
     * Builds and returns a rule set to parse the root node
     * @return \FeedIo\RuleSet
     */
    public function buildFeedRuleSet()
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Link())
            ->add(new PublicId('id'))
            ->add($this->getModifiedSinceRule('updated'))
        ;

        return $ruleSet;
    }

    /**
     * Builds and returns a rule set to parse an item
     * @return \FeedIo\RuleSet
     */
    public function buildItemRuleSet()
    {
        $ruleSet = $this->buildFeedRuleSet();
        $ruleSet->add(new Description('content'));

        return $ruleSet;
    }

}