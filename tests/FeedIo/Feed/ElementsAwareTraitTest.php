<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed;

use PHPUnit\Framework\TestCase;

class ElementsAwareTraitTest extends TestCase
{
    public function testGetElementsAsGenerator()
    {
        $object = new ElementsAwareClass();
        $object->set('foo', 'bar');

        $elements = $object->getElementsGenerator();

        $this->assertEquals('foo', $elements->key());
        $this->assertEquals('bar', $elements->current());
    }
}
