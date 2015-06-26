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
        'Y-m-d',
        'd/m/Y',
    ];

    /**
     * @var \DateTimeZone
     */
    protected $timezone;

    /**
     * @var string
     */
    protected $lastGuessedFormat = \DateTime::RFC2822;

    public function __construct()
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
     *
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
     * @throws InvalidArgumentException
     */
    public function convertToDateTime($string)
    {
        foreach ([$this->getLastGuessedFormat(), $this->guessDateFormat($string) ] as $format) {
            $date = \DateTime::createFromFormat($format, $string);
            if ($date instanceof \DateTime) {
                $date->setTimezone($this->getTimezone());

                return $date;
            }
        }

        throw new \InvalidArgumentException('Impossible to convert date : '.$string);
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
