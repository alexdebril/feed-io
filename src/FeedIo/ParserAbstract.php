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

use \DOMDocument;
use FeedIo\Feed\ItemInterface;
use FeedIo\Parser\MissingFieldsException;
use FeedIo\Parser\UnsupportedFormatException;
use Psr\Log\LoggerInterface;

abstract class ParserAbstract
{
    /**
     * System's time zone
     *
     * @var \DateTimeZone
     */
    static protected $timezone;

    /**
     * List of mandatory fields
     *
     * @var array[string]
     */
    protected $mandatoryFields = array();

    /**
     * supported date formats
     *
     * @var array[string]
     */
    protected $dateFormats = array();

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    /**
     * @param DOMDocument $document
     * @param FeedInterface $feed
     * @param \DateTime $modifiedSince
     * @return FeedInterface
     * @throws Parser\MissingFieldsException
     * @throws Parser\UnsupportedFormatException
     */
    public function parse(DOMDocument $document, FeedInterface $feed, \DateTime $modifiedSince)
    {
        if (!$this->canHandle($document)) {
            throw new UnsupportedFormatException('this is not a supported format');
        }

        $this->checkBodyStructure($document);

        return $this->parseBody($document, $feed, $modifiedSince);
    }

    /**
     * @param DOMDocument $document
     * @return $this
     * @throws Parser\MissingFieldsException
     */
    protected function checkBodyStructure(DOMDocument $document)
    {
        $errors = array();

        $element = $document->documentElement;
        foreach( $this->mandatoryFields as $field ) {
            $list = $element->getElementsByTagName($field);
            if ( 0 === $list->length ) {
                $errors[] = $field;
            }
        }

        if ( ! empty($errors) ) {
            $message = "missing mandatory field(s) : " . implode(',', $errors);
            $this->logger->warning($message);
            throw new MissingFieldsException($message);
        }

        return $this;
    }

    /**
     *
     * @param array $dates
     */
    public function setDateFormats(array $dates)
    {
        $this->dateFormats = $dates;
    }

    /**
     *
     * @param string $date
     * @return string date Format
     * @throws InvalidArgumentException
     */
    public function guessDateFormat($date)
    {
        foreach ($this->dateFormats as $format) {
            $test = \DateTime::createFromFormat($format, $date);
            if ($test instanceof \DateTime) {
                return $format;
            }
        }

        throw new \InvalidArgumentException('Impossible to guess date format : ' . $date);
    }

    /**
     * @param FeedInterface $feed
     * @param ItemInterface $item
     * @return $this
     */
    public function addValidItem(FeedInterface $feed, ItemInterface $item, \DateTime $modifiedSince)
    {
        if ($this->isValid($item, $modifiedSince)) {
            $feed->add($item);
        }

        return $this;
    }

    /**
     * @param ItemInterface $item
     * @param \DateTime $modifiedSince
     * @return bool
     */
    public function isValid(ItemInterface $item, \DateTime $modifiedSince)
    {
        if ( $item->getLastModified() instanceof \DateTime ) {
            return $item->getLastModified() > $modifiedSince;
        }

        return false;
    }

    /**
     * Creates a DateTime instance for the given string. Default format is RFC2822
     * @param string $string
     * @param string $format
     */
    static public function convertToDateTime($string, $format = DateTime::RFC2822)
    {
        $date = DateTime::createFromFormat($format, $string);

        if (!$date instanceof \DateTime) {
            throw new \InvalidArgumentException("date is the wrong format : {$string} - expected {$format}");
        }

        $date->setTimezone(self::getSystemTimezone());

        return $date;
    }

    /**
     * Returns the system's timezone
     *
     * @return \DateTimeZone
     */
    static public function getSystemTimezone()
    {
        if (is_null(self::$timezone)) {
            self::$timezone = new \DateTimeZone(date_default_timezone_get());
        }

        return self::$timezone;
    }

    /**
     * Reset the system's time zone
     */
    static public function resetTimezone()
    {
        self::$timezone = null;
    }

    /**
     * Tells if the parser can handle the feed or not
     * @param \DOMDocument $document
     * @return mixed
     */
    abstract public function canHandle(\DOMDocument $document);

    /**
     * @param DOMDocument $document
     * @param FeedInterface $feed
     * @param \DateTime $modifiedSince
     * @return FeedInterface
     */
    abstract protected function parseBody(\DOMDocument $document, FeedInterface $feed, \DateTime $modifiedSince);
} 