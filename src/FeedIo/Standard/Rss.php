<?php declare(strict_types=1);
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
use FeedIo\Reader\Document;
use FeedIo\Rule\Author;
use FeedIo\Rule\Description;
use FeedIo\Rule\Language;
use FeedIo\Rule\Link;
use FeedIo\Rule\PublicId;
use FeedIo\Rule\Media;
use FeedIo\Rule\Category;
use FeedIo\RuleSet;

class Rss extends XmlAbstract
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
     * @return \DOMDocument
     */
    public function format(\DOMDocument $document) : \DOMDocument
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
     * @param  Document $document
     * @return boolean
     */
    public function canHandle(Document $document) : bool
    {
        return static::ROOT_NODE_TAGNAME === $document->getDOMDocument()->documentElement->tagName;
    }

    /**
     * @param  DOMDocument $document
     * @return \DomElement
     */
    public function getMainElement(\DOMDocument $document) : \DOMElement
    {
        return $document->documentElement->getElementsByTagName(static::CHANNEL_NODE_TAGNAME)->item(0);
    }

    /**
     * @return \FeedIo\RuleSet
     */
    public function buildFeedRuleSet() : RuleSet
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet->add(new Language());

        return $ruleSet;
    }

    /**
     * @return \FeedIo\RuleSet
     */
    public function buildItemRuleSet() : RuleSet
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Author(), ['dc:creator'])
            ->add(new PublicId())
            ->add(new Media(), ['media:thumbnail'])
            ;

        return $ruleSet;
    }

    /**
     * @return \FeedIo\RuleSet
     */
    protected function buildBaseRuleSet() : RuleSet
    {
        $ruleSet = parent::buildBaseRuleSet();
        $ruleSet
            ->add(new Link())
            ->add(new Description(), ['content:encoded'])
            ->add($this->getModifiedSinceRule(static::DATE_NODE_TAGNAME), ['lastBuildDate', 'lastPubDate'])
            ->add(new Category());

        return $ruleSet;
    }
}
