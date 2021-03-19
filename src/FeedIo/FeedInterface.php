<?php declare(strict_types=1);

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
     * @return string|null
     */
    public function getUrl() : ? string;

    public function setUrl(string $url = null) : FeedInterface;

    public function getLanguage(): ? string ;

    public function setLanguage(string $language = null): FeedInterface;

    public function getLogo() : ? string ;

    public function setLogo(string $logo = null) : FeedInterface;

    public function add(ItemInterface $item) : FeedInterface;

    public function newItem() : ItemInterface;

    public function getNS(): ?ArrayIterator;

    public function addNS(string $ns, string $dtd) : FeedInterface;

    public function setStyleSheet(StyleSheet $styleSheet): FeedInterface;

    public function getStyleSheet(): ? StyleSheet;
}
