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


use DOMDocument;
use FeedIo\Rule\Description;
use FeedIo\Rule\Link;
use FeedIo\Rule\PublicId;
use FeedIo\StandardAbstract;

class Rss extends StandardAbstract
{
    /**
     * RSS document must have a <rss> root node
     */
    const ROOT_NODE_TAGNAME = 'rss';

    /**
     * Formats the document according to the standard's specification
     * @param \DOMDocument $document
     * @return mixed
     */
    public function format(\DOMDocument $document)
    {
        $rss = $document->createElement('rss');
        $rss->setAttribute('version', '2.0');
        $channel = $document->createElement('channel');
        $rss->appendChild($channel);
        $document->appendChild($rss);

        return $document;
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
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Link())
            ->add(new PublicId())
            ->add(new Description())
            ->add($this->getModifiedSinceRule('pubDate'));

        return $ruleSet;
    }

}
