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
use FeedIo\RuleSet;
use FeedIo\Rule\Description;
use FeedIo\Rule\Link;
use FeedIo\Rule\Structure;
use FeedIo\StandardAbstract;

class Rdf extends StandardAbstract
{
    /**
     * RDF document must have a <rdf> root node
     */
    const ROOT_NODE_TAGNAME = 'rdf';

    /**
     * Formats the document according to the standard's specification
     * @param \DOMDocument $document
     * @return mixed
     */
    public function format(\DOMDocument $document)
    {
        $rdf = $document->createElement('rdf');
        $channel = $document->createElement('channel');
        $rdf->appendChild($channel);
        $document->appendChild($rdf);

        return $document;
    }

    /**
     * Tells if the parser can handle the feed or not
     * @param \DOMDocument $document
     * @return boolean
     */
    public function canHandle(\DOMDocument $document)
    {
        return false !== strpos($document->documentElement->tagName, self::ROOT_NODE_TAGNAME);
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
     * @return RuleSet
     */
    public function buildFeedRuleSet()
    {
        $ruleSet = new RuleSet;
        $ruleSet->add(new Structure('channel', $this->buildItemRuleSet()));
        
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
            ->add(new Description())
            ->add($this->getModifiedSinceRule('dc:date'));

        return $ruleSet;
    }

}
