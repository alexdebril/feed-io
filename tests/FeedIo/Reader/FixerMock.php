<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21/04/15
 * Time: 22:31
 */
namespace FeedIo\Reader;

use FeedIo\FeedInterface;
use Psr\Log\LoggerInterface;

use \PHPUnit\Framework\TestCase;

class FixerMock extends FixerAbstract
{

    /**
     * @var Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param  LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger) : FixerAbstract
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param  FeedInterface $feed
     * @return $this
     */
    public function correct(FeedInterface $feed) : FixerAbstract
    {
        $feed->setTitle('corrected');

        return $this;
    }
}
