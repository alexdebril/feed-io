<?php

declare(strict_types=1);

namespace FeedIo\Rule;

use DomDocument;
use DOMElement;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Feed\ItemInterface;
use FeedIo\Feed\NodeInterface;
use FeedIo\Parser\UrlGenerator;
use FeedIo\RuleAbstract;

class Media extends RuleAbstract
{
    public const NODE_NAME = 'enclosure';

    public const MRSS_NAMESPACE = "http://search.yahoo.com/mrss/";

    protected string $urlAttributeName = 'url';

    protected UrlGenerator $urlGenerator;

    public function __construct(string $nodeName = null)
    {
        $this->urlGenerator = new UrlGenerator();
        parent::__construct($nodeName);
    }

    public function getUrlAttributeName(): string
    {
        return $this->urlAttributeName;
    }

    public function setUrlAttributeName(string $name): void
    {
        $this->urlAttributeName = $name;
    }

    public function setProperty(NodeInterface $node, DOMElement $element): void
    {
        if ($node instanceof ItemInterface) {
            $media = $node->newMedia();
            $media->setNodeName($element->nodeName);

            switch ($element->nodeName) {
                case 'media:group':
                    $this->initMedia($media, $element);
                    $this->setUrl($media, $node, $this->getChildAttributeValue($element, 'content', 'url', static::MRSS_NAMESPACE));
                    break;
                case 'media:content':
                    $this->initMedia($media, $element);
                    $this->setUrl($media, $node, $this->getAttributeValue($element, "url"));
                    break;
                default:
                    $media
                        ->setType($this->getAttributeValue($element, 'type'))
                        ->setLength($this->getAttributeValue($element, 'length'));
                    $this->setUrl($media, $node, $this->getAttributeValue($element, $this->getUrlAttributeName()));
                    break;
            }
            $node->addMedia($media);
        }
    }

    protected function setUrl(MediaInterface $media, NodeInterface $node, string $url = null): void
    {
        if (! is_null($url)) {
            $media->setUrl(
                $this->urlGenerator->getAbsolutePath($url, $node->getHost())
            );
        }
    }

    public function createMediaElement(DomDocument $document, MediaInterface $media): DOMElement
    {
        $element = $document->createElement($this->getNodeName());
        $element->setAttribute($this->getUrlAttributeName(), $media->getUrl());
        $element->setAttribute('type', $media->getType() ?? '');
        $element->setAttribute('length', $media->getLength() ?? '');

        return $element;
    }

    protected function initMedia(MediaInterface $media, DOMElement $element): void
    {
        $media->setTitle($this->getChildValue($element, 'title', static::MRSS_NAMESPACE));
        $media->setDescription($this->getChildValue($element, 'description', static::MRSS_NAMESPACE));
        $media->setThumbnail($this->getChildAttributeValue($element, 'thumbnail', 'url', static::MRSS_NAMESPACE));
    }

    protected function hasValue(NodeInterface $node): bool
    {
        return $node instanceof ItemInterface && !! $node->getMedias();
    }

    protected function addElement(DomDocument $document, DOMElement $rootElement, NodeInterface $node): void
    {
        if ($node instanceof ItemInterface) {
            foreach ($node->getMedias() as $media) {
                $rootElement->appendChild($this->createMediaElement($document, $media));
            }
        }
    }
}
