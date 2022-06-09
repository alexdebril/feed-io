<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DateTime;
use DateTimeZone;
use Psr\Log\LoggerInterface;

class DateTimeBuilder implements DateTimeBuilderInterface
{
    /**
     * Supported date formats
     */
    protected array $dateFormats = [
        \DateTime::RFC2822,
        \DateTime::RFC822,
        \DateTime::ATOM,
        \DateTime::RFC3339,
        \DateTime::RFC3339_EXTENDED,
        \DateTime::RSS,
        \DateTime::W3C,
        'Y-m-d\TH:i:s.uP',
        'Y-m-d\TH:i:s.uvP',
        'Y-m-d\TH:i:s',
        'Y-m-d\TH:iP',
        'Y-m-d',
        'd/m/Y',
        'D, d M Y H:i O',
        'D, d M Y H:i:s O',
        'D M d Y H:i:s e',
        '*, m#d#Y - H:i',
        'D, d M Y H:i:s \U\T',
        '*, d M* Y H:i:s e',
        '*, d M Y',
    ];

    protected ?DateTimeZone $feedTimezone = null;

    protected DateTimeZone $serverTimezone;

    protected string $lastGuessedFormat = DateTime::RFC2822;

    public function __construct(protected ?LoggerInterface $logger = null)
    {
        $this->setTimezone(new DateTimeZone(date_default_timezone_get()));
    }

    public function addDateFormat(string $dateFormat): DateTimeBuilderInterface
    {
        $this->dateFormats[] = $dateFormat;

        return $this;
    }

    public function setDateFormats(array $dateFormats): DateTimeBuilderInterface
    {
        $this->dateFormats = $dateFormats;

        return $this;
    }

    public function getLastGuessedFormat(): string
    {
        return $this->lastGuessedFormat;
    }

    public function guessDateFormat(string $date): ?string
    {
        foreach ($this->dateFormats as $format) {
            $test = DateTime::createFromFormat($format, $date);
            if ($test instanceof \DateTime) {
                $this->lastGuessedFormat = $format;

                return $format;
            }
        }

        return null;
    }

    public function convertToDateTime(string $string): DateTime
    {
        $string = trim($string);
        foreach ([$this->getLastGuessedFormat(), $this->guessDateFormat($string) ] as $format) {
            $date = $this->newDate((string) $format, $string);
            if ($date instanceof \DateTime) {
                $date->setTimezone($this->getTimezone());

                return $date;
            }
        }

        if ($this->logger) {
            $this->logger->notice("unsupported date format : {$string}, returning current datetime");
        }

        $date = new DateTime(timezone: $this->getFeedTimezone());
        $date->setTimezone($this->getTimezone());

        return $date;
    }

    public function getFeedTimezone(): ?DateTimeZone
    {
        return $this->feedTimezone;
    }

    public function setFeedTimezone(DateTimeZone $timezone): void
    {
        $this->feedTimezone = $timezone;
    }

    public function resetFeedTimezone(): void
    {
        $this->feedTimezone = null;
    }

    public function getServerTimezone(): ?DateTimeZone
    {
        return $this->serverTimezone;
    }

    public function setServerTimezone(DateTimeZone $timezone): void
    {
        $this->serverTimezone = $timezone;
    }

    public function getTimezone(): ?DateTimeZone
    {
        return $this->getServerTimezone();
    }

    public function setTimezone(DateTimeZone $timezone): void
    {
        $this->setServerTimezone($timezone);
    }

    protected function newDate(string $format, string $string): DateTime|bool
    {
        if (!! $this->getFeedTimezone()) {
            return DateTime::createFromFormat($format, $string, $this->getFeedTimezone());
        }

        return DateTime::createFromFormat($format, $string);
    }
}
