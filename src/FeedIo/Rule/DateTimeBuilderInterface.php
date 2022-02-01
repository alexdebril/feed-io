<?php

declare(strict_types=1);

namespace FeedIo\Rule;

interface DateTimeBuilderInterface
{
    /**
     * @param string $dateFormat
     * @return DateTimeBuilderInterface
     */
    public function addDateFormat(string $dateFormat): DateTimeBuilderInterface;

    /**
     * @param  array $dateFormats
     * @return DateTimeBuilderInterface
     */
    public function setDateFormats(array $dateFormats): DateTimeBuilderInterface;

    /**
     * @return string
     */
    public function getLastGuessedFormat(): string;

    /**
     * Tries to guess the date's format from the list
     * @param  string                   $date
     * @return string|null             date Format
     */
    public function guessDateFormat(string $date): ?string;

    /**
     * Creates a DateTime instance for the given string. Default format is RFC2822
     * @param  string                   $string
     * @return \DateTime
     */
    public function convertToDateTime(string $string): \DateTime;

    /**
     * @return \DateTimeZone
     */
    public function getFeedTimezone(): ?\DateTimeZone;

    /**
     * Specifies the feed's timezone. Do this it the timezone is missing
     *
     * @param \DateTimeZone $timezone
     */
    public function setFeedTimezone(\DateTimeZone $timezone): void;

    /**
     * Resets feedTimezone to null.
     */
    public function resetFeedTimezone(): void;
}
