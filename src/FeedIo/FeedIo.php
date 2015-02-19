<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
namespace FeedIo;

use \FeedIo\Reader;
use \FeedIo\Rule\DateTimeBuilder;
use \FeedIo\Adapter\ClientInterface
use \Psr\Log\LoggerInterface;

class FeedIo
{

    /**
     * @var \FeedIo\Reader
     */
    protected $reader;
    
    /**
     * @var \FeedIo\Rule\DateTimeBuilder
     */
    protected $dateTimeBuilder;
    
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    
    /**
     * @var array
     */
    protected $standards;

    /**
     * @param \FeedIo\Adapter\ClientInterface $client
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(ClientInterface $client, LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->reader = new Reader($client, $logger);
        $this->dateTimeBuilder = new DateTimeBuilder;
        
        $this->loadCommonStandards();
    }
    
    /**
     * @return $this
     */
    protected function loadCommonStandards()
    {
        $standards = $this->getCommonStandards();
        foreach ($standards as $name => $standard) {
            $this->addStandard($name, $standard);
        }
        
        return $this;
    }
    
    /**
     * @return array
     */
    public function getCommonStandards()
    {
        return array(
            'atom' => new Atom($this->dateTimeBuilder),
            'rss' => new Rss($this->dateTimeBuilder),
        );
    }
    
    /**
     * @param string $name
     * @param \FeedIo\StandardAbstract $standard
     * @return $this
     */
    public function addStandard($name, StandardAbstract $standard)
    {
        $name = strtolower($name);
        $this->standards[$name] = $standard;
        $this->reader->addParser(
                            new Parser($standard, $this->logger)
                        );
        
        return $this;
    }
    
    /**
     * @param $url
     * @param FeedInterface $feed
     * @param \DateTime $modifiedSince
     * @return \FeedIo\Reader\Result
     */
    public function read($url, FeedInterface $feed = null, \DateTime $modifiedSince = null)
    {
        if ( is_null($feed) ) {
            $feed = new Feed;
        }
       
        $this->logAction($feed, "read access : $url into a %s instance");
        return $this->reader->read($url, $feed, $modifiedSince);
    }
    
    /**
     * @param FeedInterface $feed
     * @param string $standard Standard's name
     * @return \DomDocument
     */ 
    public function format(FeedInterface $feed, $standard)
    {
        $this->logAction($feed, "formatting a %s in $standard format");
        
        $formatter = new Formatter($this->getStandard($standard), $this->logger);
        
        return $formatter->toDom($feed);
    }
    
    /**
     * @param \FeedIo\FeedInterface $feed
     * @return \DomDocument
     */
    public function toRss(FeedInterface $feed)
    {
        return $this->format($feed, 'rss');
    }
    
    /**
     * @param \FeedIo\FeedInterface $feed
     * @return \DomDocument
     */
    public function toAtom(FeedInterface $feed)
    {
        return $this->format($feed, 'atom');
    }
    
    /**
     * @param string $name
     * @return \FeedIo\StandardAbstract
     * @throws \InvalidArgumentException
     */
    public function getStandard($name)
    {
        $name = strtolower($name);
        if ( array_key_exists($name, $this->standards) ) {
            return $this->standards[$name];
        }
        
        throw new \InvalidArgument("no standard found for $name");
    }
    
    /**
     * @param \FeedIo\FeedInterface $feed
     * @param string $message
     * @return $this
     */
    protected function logAction(FeedInterface $feed, $message)
    {
        $class = get_class($feed);
        $this->logger->debug(sprintf($message, $class));
        
        return $this;
    }
}
