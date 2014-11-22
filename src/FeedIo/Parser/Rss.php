<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;


use DOMDocument;
use FeedIo\Parser\Rule\Description;
use FeedIo\Parser\Rule\Link;
use FeedIo\Parser\Rule\PublicId;
use FeedIo\Parser\Rule\Title;
use FeedIo\ParserAbstract;

class Rss extends ParserAbstract
{
    /**
     * RSS document must have a <rss> root node
     */
    const ROOT_NODE_TAGNAME = 'rss';

    /**
     * @return RuleSet
     */
    public function buildFeedRuleSet()
    {
        $ruleSet = $this->buildItemRuleSet();
        $ruleSet->add($this->getModifiedSinceRule('lastPubDate'));

        return $ruleSet;
    }

    /**
     * @return RuleSet
     */
    public function buildItemRuleSet()
    {
        $ruleSet = new RuleSet();
        $ruleSet->add(new Title())
            ->add(new Link())
            ->add(new PublicId())
            ->add(new Description())
            ->add($this->getModifiedSinceRule('pubDate'));

        return $ruleSet;
    }

    /**
     * Tells if the parser can handle the feed or not
     * @param \DOMDocument $document
     * @return boolean
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
        return $document->documentElement->getElementsByTagName('channel')->item(0);
    }

}
