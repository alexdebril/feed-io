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
        $this->reader = new Reader($client, $logger);
        $this->dateTimeBuilder = new DateTimeBuilder;
        $this->logger = $logger;
        $this->loadCommonStandards();
    }
    
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
     */
    public function addStandard($name, StandardAbstract $standard)
    {
        $this->standards[$name] = $standard;
        $this->reader->addParser(
                            new Parser($standard, $this->logger)
                        );
        
        return $this;
    }
    
    /**
     *
     */
    public function read($url, FeedInterface $feed, \DateTime $modifiedSince = null)
    {
    
    }
    
    public function format(FeedInterface $feed, $standard)
    {
    
    }
    
    public function toRss(FeedInterface $feed)
    {
    
    }
    
    public function toAtom(FeedInterface $feed)
    {
    
    }
    
    public function getStandard($name)
    {
    
    }
    
    
}
