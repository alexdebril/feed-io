<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 23/11/14
 * Time: 17:19
 */

namespace FeedIo;


use FeedIo\Feed\ItemInterface;

abstract class FormatterAbstract
{

    /**
     * Prepares the DOM Document according to the format's specifications
     * @param \DOMDocument $document
     * @return \DOMDocument
     */
    abstract public function prepare(\DOMDocument $document);

    /**
     * @param \DOMDocument $document
     * @param FeedInterface $feed
     * @return $this
     */
    abstract public function setHeaders(\DOMDocument$document, FeedInterface $feed);

    /**
     * @param \DOMDocument $document
     * @param ItemInterface $item
     * @return $this
     */
    abstract public function addItem(\DOMDocument$document, ItemInterface $item);

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

        return $this->prepare($document);
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