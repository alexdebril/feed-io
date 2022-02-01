<?php

declare(strict_types=1);

namespace FeedIo;

use ArrayIterator;
use FeedIo\Feed\NodeInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\StyleSheet;

/**
 * Interface FeedInterface
 * Represents the top node of a news feed
 * @package FeedIo
 */
interface FeedInterface extends \Iterator, \Countable, NodeInterface
{
    /**
     * This method MUST return the feed's full URL
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Sets the feed's URL. Precisely the URL to hit to get the stream
     *
     * @param string|null $url
     * @return FeedInterface
     */
    public function setUrl(string $url = null): FeedInterface;

    /**
     * Returns feed's description
     *
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * Sets feed's description
     *
     * @param string|null $description
     * @return FeedInterface
     */
    public function setDescription(string $description = null): FeedInterface;

    /**
     * @return string|null
     */
    public function getLanguage(): ?string ;

    /**
     * @param string|null $language
     * @return FeedInterface
     */
    public function setLanguage(string $language = null): FeedInterface;

    /**
     * @return string|null
     */
    public function getLogo(): ?string ;

    /**
     * @param string|null $logo
     * @return FeedInterface
     */
    public function setLogo(string $logo = null): FeedInterface;

    /**
     * @param ItemInterface $item
     * @return FeedInterface
     */
    public function add(ItemInterface $item): FeedInterface;

    /**
     * Returns a fresh item compatible with the feed
     *
     * @return ItemInterface
     */
    public function newItem(): ItemInterface;

    /**
     * @return ArrayIterator|null
     */
    public function getNS(): ?ArrayIterator;

    /**
     * @param string $ns
     * @param string $dtd
     * @return FeedInterface
     */
    public function addNS(string $ns, string $dtd): FeedInterface;

    /**
     * @param StyleSheet $styleSheet
     * @return FeedInterface
     */
    public function setStyleSheet(StyleSheet $styleSheet): FeedInterface;

    /**
     * @return StyleSheet|null
     */
    public function getStyleSheet(): ?StyleSheet;
}
