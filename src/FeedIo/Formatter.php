<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/11/14
 * Time: 17:19
 */

namespace FeedIo;


use FeedIo\Feed\ItemInterface;
use Psr\Log\LoggerInterface;

class Formatter
{

    /**
     * @var StandardAbstract
     */
    protected $standard;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param StandardAbstract $standard
     * @param LoggerInterface $logger
     */
    function __construct(StandardAbstract $standard, LoggerInterface $logger)
    {
        $this->standard = $standard;
        $this->logger = $logger;
    }


    /**
     * @param \DOMDocument $document
     * @param FeedInterface $feed
     * @return $this
     */
    public function setHeaders(\DOMDocument $document, FeedInterface $feed)
    {
        $rules = $this->standard->getFeedRuleSet()->getRules();

    }

    /**
     * @param \DOMDocument $document
     * @param ItemInterface $item
     * @return $this
     */
    public function addItem(\DOMDocument $document, ItemInterface $item)
    {

    }

    public function applyRules(RuleSet $ruleSet, DOMDocument $document, ItemInterface $feed)
    {
        $rules = $ruleSet->getRules();
        foreach( $rules as $rule ) {

        }
    }

    /**
     * @return \DOMDocument
     */
    public function getEmptyDocument()
    {
        return new \DOMDocument('1.0', 'utf-8');
    }

    /**
     * @return \DOMDocument
     */
    public function getDocument()
    {
        $document = $this->getEmptyDocument();

        return $this->standard->format($document);
    }

    /**
     * @param FeedInterface $feed
     * @return string
     */
    public function toString(FeedInterface $feed)
    {
        $document = $this->toDom($feed);

        return$document->saveXML();
    }

    /**
     * @param FeedInterface $feed
     * @return \DomDocument
     */
    public function toDom(FeedInterface $feed)
    {
        $document = $this->getDocument();

        $this->setHeaders($document, $feed);
        $this->setItems($document, $feed);

        return $document;
    }

    /**
     * @param \DOMDocument $document
     * @param FeedInterface $feed
     * @return $this
     */
    public function setItems(\DOMDocument $document, FeedInterface $feed)
    {
        foreach ($feed as $item) {
            $this->addItem($document, $item);
        }

        return $this;
    }

}