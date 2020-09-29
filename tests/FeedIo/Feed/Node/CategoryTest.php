<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Node;

use \PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    /**
     * @var \FeedIo\Feed\Item\Category
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Category;
    }

    public function testScheme()
    {
        $scheme = 'http://...';
        $this->object->setScheme($scheme);

        $this->assertEquals($scheme, $this->object->getScheme());
    }

    public function testLabel()
    {
        $label = 'a nice label';
        $this->object->setLabel($label);

        $this->assertEquals($label, $this->object->getLabel());
    }

    public function testTerm()
    {
        $term = 'nice';
        $this->object->setTerm($term);

        $this->assertEquals($term, $this->object->getTerm());
    }
}
