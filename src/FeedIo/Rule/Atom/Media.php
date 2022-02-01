<?php

declare(strict_types=1);

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Rule\Media as BaseMedia;

class Media extends BaseMedia
{
    public const NODE_NAME = 'link';

    /**
     * @inheritDoc
     */
    public function createMediaElement(\DomDocument $document, MediaInterface $media): \DOMElement
    {
        $element = parent::createMediaElement($document, $media);
        $element->setAttribute('rel', 'enclosure');

        return $element;
    }
}
