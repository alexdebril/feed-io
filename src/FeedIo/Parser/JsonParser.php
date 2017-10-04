<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;

use FeedIo\Feed\Item;
use FeedIo\FeedInterface;
use FeedIo\ParserAbstract;
use FeedIo\Reader\Document;

class JsonParser extends ParserAbstract
{

    /**
     * @param Document $document
     * @param FeedInterface $feed
     * @return FeedInterface
     */
    public function parseContent(Document $document, FeedInterface $feed) : FeedInterface
    {
        $data = $document->getJsonAsArray();
        $feed->setTitle($this->readOffset($data, 'title'));
        $feed->setDescription($this->readOffset($data, 'description'));
        $feed->setLink($this->readOffset($data, 'feed_url'));
        $feed->setUrl($this->readOffset($data, 'home_page_url'));

        if (array_key_exists('items', $data)) {
            $this->parseItems($data['items'], $feed);
        }

        return $feed;
    }

    /**
     * @param Document $document
     * @param iterable $mandatoryFields
     * @throws MissingFieldsException
     * @return bool
     */
    public function checkBodyStructure(Document $document, iterable $mandatoryFields) : bool
    {
        $data = $document->getJsonAsArray();

        foreach ($mandatoryFields as $mandatoryField) {
            if (! array_key_exists($mandatoryField, $data)) {
                throw new MissingFieldsException("Missing {$mandatoryField} in the JSON Feed");
            }
        }

        return true;
    }

    /**
     * @param iterable $items
     * @param FeedInterface $feed
     * @return JsonParser
     */
    public function parseItems(iterable $items, FeedInterface $feed) : JsonParser
    {
        foreach ($items as $dataItem) {
            $item = new Item();
            $item->setPublicId($this->readOffset($dataItem, 'id'));
            $item->setTitle($this->readOffset($dataItem, 'title'));
            $item->setLastModified(new \DateTime($this->readOffset($dataItem, 'date_published')));
            $contentHtml = $this->readOffset($dataItem, 'content_html');
            $item->setDescription($this->readOffset($dataItem, 'content_text', $contentHtml));
            $feed->add($item);
        }

        return $this;
    }

    /**
     * @param array $data
     * @param string $offsetName
     * @param string|null $default
     * @return null|string
     */
    public function readOffset(array $data, string $offsetName, string $default = null) : ? string
    {
        if (array_key_exists($offsetName, $data)) {
            return $data[$offsetName];
        }

        return $default;
    }
}
