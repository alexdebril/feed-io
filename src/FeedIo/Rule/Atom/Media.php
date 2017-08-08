<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Rule\Atom;

use FeedIo\Feed\Item\MediaInterface;
use FeedIo\Rule\Media as BaseMedia;

class Media extends BaseMedia
{
    const NODE_NAME = 'link';

    /**
     * @inheritDoc
     */
    public function createMediaElement(\DomDocument $document, MediaInterface $media) : \DOMElement
    {
        $element = parent::createMediaElement($document, $media);
        $element->setAttribute('rel', 'enclosure');

        return $element;
    }
}
