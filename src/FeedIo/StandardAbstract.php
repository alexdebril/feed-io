<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FeedIo;

use FeedIo\Reader\Document;
use FeedIo\Rule\DateTimeBuilder;

abstract class StandardAbstract
{

    /**
     * DateTime default format
     */
    const DATETIME_FORMAT = \DateTime::RFC2822;

    /**
     * Supported format
     */
    const SYNTAX_FORMAT = '';

    /**
     * @var array
     */
    protected $mandatoryFields = array();

    /**
     * @var \FeedIo\Rule\DateTimeBuilder
     */
    protected $dateTimeBuilder;

    /**
     * @param \FeedIo\Rule\DateTimeBuilder $dateTimeBuilder
     */
    public function __construct(DateTimeBuilder $dateTimeBuilder)
    {
        $this->dateTimeBuilder = $dateTimeBuilder;
    }

    /**
     * Tells if the parser can handle the feed or not
     * @param  Document $document
     * @return boolean
     */
    abstract public function canHandle(Document $document) : bool;

    /**
     * @return \FeedIo\FormatterInterface
     */
    abstract public function getFormatter() : FormatterInterface;

    /**
     * @return string
     */
    public function getDefaultDateFormat() : string
    {
        return static::DATETIME_FORMAT;
    }

    /**
     * @return array
     */
    public function getMandatoryFields() : array
    {
        return $this->mandatoryFields;
    }

    /**
     * Returns the Format supported by the standard (XML, JSON, Text...)
     * @return string
     */
    public function getSyntaxFormat() : string
    {
        return static::SYNTAX_FORMAT;
    }
}
