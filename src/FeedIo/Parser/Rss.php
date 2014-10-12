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
use FeedIo\FeedInterface;
use FeedIo\Parser\Rule\ModifiedSince;
use FeedIo\Parser\Rule\Title;
use FeedIo\ParserAbstract;
use Psr\Log\LoggerInterface;

class Rss extends ParserAbstract
{
    /**
     * RSS document must have a <rss> root node
     */
    const ROOT_NODE_TAGNAME = 'rss';

    /**
     * @param DateTimeBuilder $dateTimeBuilder
     * @param LoggerInterface $logger
     */
    public function __construct(DateTimeBuilder $dateTimeBuilder, LoggerInterface $logger)
    {
        parent::__construct($dateTimeBuilder, $logger);

        $this->addRule(new ModifiedSince($dateTimeBuilder))
            ->addRule(new Title());
    }


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
        return $document->documentElement->getElementsByTagName('channel')->item(0);
    }

}
