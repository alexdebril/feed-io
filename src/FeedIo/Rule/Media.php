<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule;

use FeedIo\Feed\Item;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Parser\UrlGenerator;
use FeedIo\RuleAbstract;

class Media extends RuleAbstract
{
    const NODE_NAME = 'enclosure';

    const MRSS_NAMESPACE = "http://search.yahoo.com/mrss/";

    protected $urlAttributeName = 'url';

    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    public function __construct(string $nodeName = null)
    {
        $this->urlGenerator = new UrlGenerator();
        parent::__construct($nodeName);
    }

    /**
     * @return string
     */
    public function getUrlAttributeName() : string
    {
        return $this->urlAttributeName;
    }

    /**
     * @param  string $name
     */
    public function setUrlAttributeName(string $name) : void
    {
        $this->urlAttributeName = $name;
    }

    /**
     * @param  NodeInterface $node
     * @param  \DOMElement   $element
     */
    public function setProperty(NodeInterface $node, \DOMElement $element) : void
    {
        if ($node instanceof ItemInterface) {
            switch ($element->nodeName) {
                case 'media:content':
                    $this->handleMediaRoot($node, $element);
                    break;

                case 'media:group':
                    $this->handleMediaGroup($node, $element);
                    break;

                default:
                    $media = $node->newMedia();
                    $media->setNodeName($element->nodeName);
                    $media
                        ->setType($this->getAttributeValue($element, 'type'))
                        ->setLength($this->getAttributeValue($element, 'length'));
                    $this->setUrl($media, $node, $this->getAttributeValue($element, $this->getUrlAttributeName()));
                    $node->addMedia($media);
                    break;
            }
        }
    }

    /**
     * @param MediaInterface $media
     * @param NodeInterface $node
     * @param string|null $url
     */
    protected function setUrl(MediaInterface $media, NodeInterface $node, string $url = null): void
    {
        if (! is_null($url)) {
            $media->setUrl(
                $this->urlGenerator->getAbsolutePath($url, $node->getHost())
            );
        }
    }

    /**
     * @param  \DomDocument   $document
     * @param  MediaInterface $media
     * @return \DomElement
     */
    public function createMediaElement(\DomDocument $document, MediaInterface $media) : \DOMElement
    {
        $element = $document->createElement($this->getNodeName());
        $element->setAttribute($this->getUrlAttributeName(), $media->getUrl());
        $element->setAttribute('type', $media->getType() ?? '');
        $element->setAttribute('length', $media->getLength() ?? '');

        return $element;
    }

    /**
     * @param \NodeInterface $node
     * @param \DomElement $element
     * @return \MediaInterface
     */
    protected function handleMediaGroup(NodeInterface $node, \DOMElement $element): void
    {
        foreach ($element->childNodes as $mediaContentNode) {
            if (is_a($mediaContentNode, "DOMNode") && $mediaContentNode->nodeName == "media:content") {
                $this->handleMediaRoot($node, $mediaContentNode);
            }
        }
    }

    /**
     * @param \NodeInterface $node
     * @param \DomElement $element
     * @return \MediaInterface
     */
    protected function handleMediaRoot(NodeInterface $node, \DOMElement $element): void
    {
        $media = $node->newMedia();
        $this->setUrl($media, $node, $this->getAttributeValue($element, "url"));
        $media->setNodeName($element->nodeName);

        $this->handleMediaContent($element, $media);

        $tags = array(
            'media:title' => 'handleMediaTitle',
            'media:description' => 'handleMediaDescription',
            'media:thumbnail' => 'handleMediaThumbnail',
        );

        foreach ($tags as $tag => $callback) {
            $nodes = $this->findTags($element, $tag);
            if ($nodes) {
                $this->$callback($nodes, $media);
            }
        }

        $node->addMedia($media);
    }

    protected function handleMediaContent(?\DOMElement $element, MediaInterface $media) : void
    {
        $media->setUrl($this->getAttributeValue($element, "url"));
    }

    protected function handleMediaTitle(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $media->setTitle($element->nodeValue);
    }

    protected function handleMediaDescription(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $media->setDescription($element->nodeValue);
    }

    protected function handleMediaThumbnail(?\DOMNodeList $elements, MediaInterface $media) : void
    {
        $element = $elements->item(0);

        $media->setThumbnail($this->getAttributeValue($element, "url"));
    }

    /**
     * @inheritDoc
     */
    protected function hasValue(NodeInterface $node) : bool
    {
        return $node instanceof ItemInterface && !! $node->getMedias();
    }

    /**
     * @inheritDoc
     */
    protected function addElement(\DomDocument $document, \DOMElement $rootElement, NodeInterface $node) : void
    {
        foreach ($node->getMedias() as $media) {
            $rootElement->appendChild($this->createMediaElement($document, $media));
        }
    }

    /**
     * From http://www.rssboard.org/media-rss#optional-elements
     *
     * Duplicated elements appearing at deeper levels of the document tree
     * have higher priority over other levels. For example, <media:content>
     * level elements are favored over <item> level elements. The priority
     * level is listed from strongest to weakest:
     * <media:content>, <media:group>, <item>, <channel>.
     */
    public function findTags(\DOMElement $mediaContentNode, string $tag) : ? \DOMNodeList
    {
        $xpath = new \DOMXpath($mediaContentNode->ownerDocument);
        $queries = [
            $xpath->query("./descendant::$tag", $mediaContentNode),
            $xpath->query("./ancestor::media:group/child::$tag", $mediaContentNode),
            $xpath->query("./ancestor::item/child::$tag", $mediaContentNode),
            $xpath->query("./ancestor::channel/child::$tag", $mediaContentNode),
        ];

        foreach ($queries as $query) {
            if ($query->count()) {
                return $query;
            }
        }

        return null;
    }
}
