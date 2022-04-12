<?php

declare(strict_types=1);

namespace FeedIo\Standard;

use DOMDocument;
use FeedIo\Reader\Document;
use FeedIo\Rule\Atom\Author;
use FeedIo\Rule\Atom\Content;
use FeedIo\Rule\Atom\LinkNode;
use FeedIo\Rule\Atom\Logo;
use FeedIo\Rule\Atom\Summary;
use FeedIo\Rule\Description;
use FeedIo\Rule\Language;
use FeedIo\Rule\Media;
use FeedIo\Rule\PublicId;
use FeedIo\Rule\Atom\Category;
use FeedIo\RuleSet;

class Atom extends XmlAbstract
{
    public const ROOT_NODE_TAGNAME = 'feed';

    public const ITEM_NODE = 'entry';

    public const DATETIME_FORMAT = \DateTime::ATOM;

    public const MIME_TYPE = 'application/atom+xml';

    public function format(DOMDocument $document): DOMDocument
    {
        $element = $document->createElement('feed');
        $element->setAttribute('xmlns', 'http://www.w3.org/2005/Atom');
        $document->appendChild($element);

        return $document;
    }

    public function canHandle(Document $document): bool
    {
        if (!isset($document->getDOMDocument()->documentElement->tagName)) {
            return false;
        }
        return self::ROOT_NODE_TAGNAME === $document->getDOMDocument()->documentElement->tagName;
    }

    public function getMainElement(\DOMDocument $document): \DOMElement
    {
        return $document->documentElement;
    }

    public function buildFeedRuleSet(): RuleSet
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Logo())
            ->add(new Description())
        ;

        return $ruleSet;
    }

    public function buildItemRuleSet(): RuleSet
    {
        $ruleSet = $this->buildBaseRuleSet();
        $ruleSet
            ->add(new Content())
            ->add(new Summary())
            ->add(new Media(), ['media:group', 'media:content'])
        ;

        return $ruleSet;
    }

    protected function buildBaseRuleSet(): RuleSet
    {
        $ruleSet = parent::buildBaseRuleSet();
        $ruleSet
            ->add(new Category())
            ->add(new Author())
            ->add(new LinkNode())
            ->add(new PublicId('id'))
            ->add(new Language('lang'))
            ->add($this->getModifiedSinceRule('updated'), ['published']);

        return $ruleSet;
    }
}
