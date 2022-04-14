<?php

declare(strict_types=1);

namespace FeedIo\Standard;

use DOMDocument;
use FeedIo\Reader\Document;
use FeedIo\Rule\Author;
use FeedIo\Rule\Content;
use FeedIo\Rule\Description;
use FeedIo\Rule\Image;
use FeedIo\Rule\Language;
use FeedIo\Rule\Link;
use FeedIo\Rule\PublicId;
use FeedIo\Rule\Media;
use FeedIo\Rule\Category;
use FeedIo\Rule\Logo;
use FeedIo\RuleSet;

class Rss extends XmlAbstract
{
    /**
     * Format version
     */
    public const VERSION = '2.0';

    /**
     * RSS document must have a <rss> root node
     */
    public const ROOT_NODE_TAGNAME = 'rss';

    /**
     * <channel> node contains feed's metadata
     */
    public const CHANNEL_NODE_TAGNAME = 'channel';

    /**
     * publication date
     */
    public const DATE_NODE_TAGNAME = 'pubDate';

    public const MIME_TYPE = 'application/rss+xml';

    protected array $mandatoryFields = ['channel'];

    /**
     * Formats the document according to the standard's specification
     * @param  \DOMDocument $document
     * @return \DOMDocument
     */
    public function format(\DOMDocument $document): \DOMDocument
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
    public function canHandle(Document $document): bool
    {
        if (!isset($document->getDOMDocument()->documentElement->tagName)) {
            return false;
        }
        return static::ROOT_NODE_TAGNAME === $document->getDOMDocument()->documentElement->tagName;
    }

    /**
     * @param  DOMDocument $document
     * @return \DomElement
     */
    public function getMainElement(\DOMDocument $document): \DOMElement
    {
        return $document->documentElement->getElementsByTagName(static::CHANNEL_NODE_TAGNAME)->item(0);
    }

    /**
     * @return \FeedIo\RuleSet
     */
    public function buildFeedRuleSet(): RuleSet
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Description())
            ->add(new Language());

        return $ruleSet;
    }

    /**
     * @return \FeedIo\RuleSet
     */
    public function buildItemRuleSet(): RuleSet
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Author(), ['dc:creator'])
            ->add(new PublicId())
            ->add(new Image())
            ->add(new Content())
            ->add(new Media(), ['media:thumbnail', 'media:group', 'media:content'])
            ;

        return $ruleSet;
    }

    /**
     * @return \FeedIo\RuleSet
     */
    protected function buildBaseRuleSet(): RuleSet
    {
        $ruleSet = parent::buildBaseRuleSet();
        $ruleSet
            ->add(new Link())
            ->add(new Category())
            ->add(new Logo())
            ->add($this->getModifiedSinceRule(static::DATE_NODE_TAGNAME), ['dc:date', 'lastBuildDate', 'lastPubDate'])
        ;

        return $ruleSet;
    }
}
