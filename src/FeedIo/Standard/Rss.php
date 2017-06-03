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
use FeedIo\Rule\Author;
use FeedIo\Rule\Description;
use FeedIo\Rule\Link;
use FeedIo\Rule\PublicId;
use FeedIo\Rule\Media;
use FeedIo\Rule\Category;
use FeedIo\StandardAbstract;

class Rss extends StandardAbstract
{

    /**
     * Format version
     */
    const VERSION = '2.0';

    /**
     * RSS document must have a <rss> root node
     */
    const ROOT_NODE_TAGNAME = 'rss';

    /**
     * <channel> node contains feed's metadata
     */
    const CHANNEL_NODE_TAGNAME = 'channel';

    /**
     * publication date
     */
    const DATE_NODE_TAGNAME = 'pubDate';

    protected $mandatoryFields = ['channel'];

    /**
     * Formats the document according to the standard's specification
     * @param  \DOMDocument $document
     * @return mixed
     */
    public function format(\DOMDocument $document)
    {
        $rss = $document->createElement(static::ROOT_NODE_TAGNAME);
        $rss->setAttribute('version', static::VERSION);
        $channel = $document->createElement(static::CHANNEL_NODE_TAGNAME);
        $rss->appendChild($channel);
        $document->appendChild($rss);

        return $document;
    }

    /**
     * Tells if the parser can handle the feed or not
     * @param  \DOMDocument $document
     * @return boolean
     */
    public function canHandle(\DOMDocument $document)
    {
        return static::ROOT_NODE_TAGNAME === $document->documentElement->tagName;
    }

    /**
     * @param  DOMDocument $document
     * @return \DomElement
     */
    public function getMainElement(\DOMDocument $document)
    {
        return $document->documentElement->getElementsByTagName(static::CHANNEL_NODE_TAGNAME)->item(0);
    }

    /**
     * @return RuleSet
     */
    public function buildFeedRuleSet()
    {
        $ruleSet = $this->buildItemRuleSet();
        $ruleSet->add(
                    $this->getModifiedSinceRule('lastPubDate'),
                    array('lastBuildDate')
                    );

        return $ruleSet;
    }

    /**
     * @return RuleSet
     */
    public function buildItemRuleSet()
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Author())
            ->add(new Link())
            ->add(new PublicId())
            ->add(new Description())
            ->add(new Media())
            ->add($this->getModifiedSinceRule(static::DATE_NODE_TAGNAME));

        return $ruleSet;
    }

    /**
     * @return RuleSet
     */
    protected function buildBaseRuleSet()
    {
        $ruleSet = parent::buildBaseRuleSet();
        $ruleSet->add(new Category());

        return $ruleSet;
    }

}
