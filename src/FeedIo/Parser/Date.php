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


class Date
{
    /**
     * @var array
     */
    protected $dateFormats = array();

    /**
     * @var \DateTimeZone
     */
    protected $timezone;

    /**
     * @var string
     */
    protected $lastGuessedFormat = \DateTime::RFC2822;

    function __construct()
    {
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
     * @param array $dateFormats
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
                $this->lastGuessedFormat = $format;
                return $format;
            }
        }

        throw new \InvalidArgumentException('Impossible to guess date format : ' . $date);
    }

    /**
     * Creates a DateTime instance for the given string. Default format is RFC2822
     * @param string $string
     * @return \DateTime
     */
    public function convertToDateTime($string)
    {
        $date = \DateTime::createFromFormat($this->getLastGuessedFormat(), $string);

        if (!$date instanceof \DateTime) {
            $format = $this->guessDateFormat($string);
            $date = \DateTime::createFromFormat($format, $string);
        }

        $date->setTimezone($this->getTimezone());

        return $date;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param \DateTimeZone $timezone
     */
    public function setTimezone(\DateTimeZone $timezone)
    {
        $this->timezone = $timezone;
    }

}