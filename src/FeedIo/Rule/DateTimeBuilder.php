<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class DateTimeBuilder
{
    /**
     * Supported date formats
     * @var array
     */
    protected $dateFormats = [
        \DateTime::RFC2822,
        \DateTime::ATOM,
        \DateTime::RFC3339,
        \DateTime::RSS,
        \DateTime::W3C,
        'Y-m-d\TH:i:s.uP',
        'Y-m-d\TH:i:s',
        'Y-m-d',
        'd/m/Y',
        'D, d M Y H:i O',
        'D, d M Y H:i:s O',
        'D M d Y H:i:s e',
        '*, m#d#Y - H:i',
        'D, d M Y H:i:s \U\T',
    ];

    /**
     * @var \DateTimeZone
     */
    protected $feedTimezone;

    /**
     * @var \DateTimeZone
     */
    protected $serverTimezone;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var string
     */
    protected $lastGuessedFormat = \DateTime::RFC2822;

    /**
     * @param \Psr\Log\LoggerInterface        $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        if (is_null($logger)) {
            $logger = new NullLogger;
        }
        $this->logger = $logger;
        $this->setTimezone(new \DateTimeZone(date_default_timezone_get()));
    }

    /**
     * @param $dateFormat
     * @return $this
     */
    public function addDateFormat($dateFormat)
    {
        $this->dateFormats[] = $dateFormat;

        return $this;
    }

    /**
     * @param  array $dateFormats
     * @return $this
     */
    public function setDateFormats(array $dateFormats)
    {
        $this->dateFormats = $dateFormats;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastGuessedFormat()
    {
        return $this->lastGuessedFormat;
    }

    /**
     * Tries to guess the date's format from the list
     * @param  string                   $date
     * @return string|false             date Format
     * @throws InvalidArgumentException
     */
    public function guessDateFormat($date)
    {
        foreach ($this->dateFormats as $format) {
            $test = \DateTime::createFromFormat($format, $date);
            if ($test instanceof \DateTime) {
                $this->lastGuessedFormat = $format;

                return $format;
            }
        }

        return false;
    }

    /**
     * Creates a DateTime instance for the given string. Default format is RFC2822
     * @param  string                   $string
     * @return \DateTime
     */
    public function convertToDateTime($string)
    {
        $string = trim($string);
        foreach ([$this->getLastGuessedFormat(), $this->guessDateFormat($string) ] as $format) {
            $date = $this->newDate($format, $string);
            if ($date instanceof \DateTime) {
                $date->setTimezone($this->getTimezone());

                return $date;
            }
        }

        return $this->stringToDateTime($string);
    }

    /**
     * Creates a DateTime instance for the given string if the format was not catch from the list
     * @param  string                   $string
     * @return \DateTime
     * @throws InvalidArgumentException
     */
    public function stringToDateTime($string)
    {
        $this->logger->notice("unsupported date format, use strtotime() to build the DateTime instance : {$string}");

        if (false === strtotime($string)) {
            throw new \InvalidArgumentException('Impossible to convert date : '.$string);
        }
        $date = new \DateTime($string, $this->getFeedTimezone());
        $date->setTimezone($this->getTimezone());

        return $date;
    }

    /**
     * @return \DateTimeZone
     */
    public function getFeedTimezone()
    {
        return $this->feedTimezone;
    }

    /**
     * Specifies the feed's timezone. Do this it the timezone is missing
     *
     * @param \DateTimeZone $timezone
     */
    public function setFeedTimezone(\DateTimeZone $timezone)
    {
        $this->feedTimezone = $timezone;
    }

    /**
     * Resets feedTimezone to null.
     */
    public function resetFeedTimezone()
    {
        $this->feedTimezone = null;
    }

    /**
     * @return \DateTimeZone
     */
    public function getServerTimezone()
    {
        return $this->serverTimezone;
    }

    /**
     * @param \DateTimeZone $timezone
     */
    public function setServerTimezone(\DateTimeZone $timezone)
    {
        $this->serverTimezone = $timezone;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->getServerTimezone();
    }

    /**
     * @param \DateTimeZone $timezone
     */
    public function setTimezone(\DateTimeZone $timezone)
    {
        $this->setServerTimezone($timezone);
    }


    /**
     * @param $format
     * @param $string
     * @return \DateTime
     */
    protected function newDate($format, $string)
    {
        if (!! $this->getFeedTimezone()) {
            return \DateTime::createFromFormat($format, $string, $this->getFeedTimezone());
        }

        return \DateTime::createFromFormat($format, $string);
    }
}
