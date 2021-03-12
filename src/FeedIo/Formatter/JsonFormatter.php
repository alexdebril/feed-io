<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Formatter;

use FeedIo\Feed;
use FeedIo\FeedInterface;
use FeedIo\FormatterInterface;

class JsonFormatter implements FormatterInterface
{

    /**
     * @param FeedInterface $feed
     * @return string
     */
    public function toString(FeedInterface $feed) : string
    {
        return json_encode($this->toArray($feed));
    }

    /**
     * @param FeedInterface $feed
     * @return array
     */
    public function toArray(FeedInterface $feed) : array
    {
        $out =  array_filter([
            'version' => 'https://jsonfeed.org/version/1',
            'title' => $feed->getTitle(),
            'description' => $feed->getDescription(),
            'home_page_url' => $feed->getLink(),
            'feed_url' => $feed->getUrl(),
            'id' => $feed->getPublicId(),
            'icon' => $feed->getLogo(),
            'items' => iterator_to_array($this->itemsToArray($feed)),
        ]);
        $this->handleAuthor($feed, $out);

        return $out;
    }

    /**
     * @param FeedInterface $feed
     * @return iterable
     */
    public function itemsToArray(FeedInterface $feed) : iterable
    {
        foreach ($feed as $item) {
            yield $this->itemToArray($item);
        }
    }

    /**
     * @param Feed\ItemInterface $item
     * @return array
     */
    public function itemToArray(Feed\ItemInterface $item) : array
    {
        $array = $this->itemToBaseArray($item);
        $this->handleAuthor($item, $array);
        $this->handleMedia($item, $array);
        $this->handleDate($item, $array);

        return array_filter($array);
    }

    /**
     * @param Feed\ItemInterface $item
     * @return array
     */
    public function itemToBaseArray(Feed\ItemInterface $item) : array
    {
        $offset = $this->isHtml($item->getDescription()) ? 'content_html':'content_text';
        return [
            'id' => $item->getPublicId(),
            'title' => $item->getTitle(),
            $offset => $item->getDescription(),
            'url' => $item->getLink(),
        ];
    }

    /**
     * @param $string
     * @return bool
     */
    public function isHtml(?string $string) : bool
    {
        return !! $string && $string !== strip_tags($string);
    }

    public function handleAuthor(Feed\NodeInterface $node, array &$array) : array
    {
        if (! is_null($node->getAuthor())) {
            $array['authors'] = [array_filter([
                'name' => $node->getAuthor()->getName(),
                'url' => $node->getAuthor()->getUri(),
            ])];
        }

        return $array;
    }

    /**
     * @param Feed\ItemInterface $item
     * @param array $array
     * @return array
     */
    public function handleMedia(Feed\ItemInterface $item, array &$array) : array
    {
        if ($item->hasMedia()) {
            $attachments = [];
            /** @var Feed\Item\MediaInterface $media */
            foreach ($item->getMedias() as $media) {
                $attachments[] = array_filter([
                    'url' => $media->getUrl(),
                    'mime_type' => $media->getType(),
                    'title' => $media->getTitle(),
                    'size_in_bytes' => $media->getLength(),
                ]);
            }
            $array['attachments'] = $attachments;
        }
        return $array;
    }

    /**
     * @param Feed\ItemInterface $item
     * @param array $array
     * @return array
     */
    public function handleDate(Feed\ItemInterface $item, array &$array) : array
    {
        if (! is_null($item->getLastModified())) {
            $array['date_published'] = $item->getLastModified()->format(\DateTime::RFC3339);
        }

        return $array;
    }
}
