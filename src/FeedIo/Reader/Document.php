<?php

declare(strict_types=1);

namespace FeedIo\Reader;

use DOMDocument;

class Document
{
    protected string $content;

    protected ?DOMDocument $domDocument = null;

    protected ?array $jsonArray = null;

    public function __construct(string $content)
    {
        $invalid_characters = '/[^\x9\xa\x20-\xD7FF\xE000-\xFFFD]/';
        $content = preg_replace($invalid_characters, '', $content);
        $this->content = trim(str_replace("\xEF\xBB\xBF", '', $content));
    }

    public function startWith(string $character): bool
    {
        return mb_substr($this->content, 0, 1) === $character;
    }

    public function isJson(): bool
    {
        return $this->startWith('{');
    }

    public function isXml(): bool
    {
        return $this->startWith('<');
    }

    public function getDOMDocument(): DOMDocument
    {
        if (is_null($this->domDocument)) {
            $this->domDocument = $this->loadDomDocument();
        }

        return $this->domDocument;
    }

    public function getJsonAsArray(): array
    {
        if (is_null($this->jsonArray)) {
            $this->jsonArray = $this->loadJsonAsArray();
        }

        return $this->jsonArray;
    }

    protected function loadDomDocument(): DOMDocument
    {
        if (! $this->isXml()) {
            throw new \LogicException('this document is not a XML stream');
        }

        set_error_handler(

        /**
         * @param string $errno
         */
            function ($errno, $errstr) {
                throw new \InvalidArgumentException("malformed xml string. parsing error : $errstr ($errno)");
            }
        );

        try {
            $domDocument = new \DOMDocument();
            $domDocument->loadXML($this->content);
        } finally {
            restore_error_handler();
        }

        return $domDocument;
    }

    protected function loadJsonAsArray(): array
    {
        if (! $this->isJson()) {
            throw new \LogicException('this document is not a JSON stream');
        }

        return json_decode($this->content, true);
    }
}
