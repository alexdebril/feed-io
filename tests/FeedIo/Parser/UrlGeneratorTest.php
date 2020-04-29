<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;

use PHPUnit\Framework\TestCase;

class UrlGeneratorTest extends TestCase
{
    public function testGetAbsolutePath()
    {
        $urlGenerator = new UrlGenerator();
        $this->assertEquals(
            'http://localhost/folder/file.ext',
            $urlGenerator->getAbsolutePath('/folder/file.ext', 'http://localhost')
        );
    }

    public function testGenerateAbsolutePath()
    {
        $urlGenerator = new UrlGenerator();
        $this->assertEquals(
            'http://localhost/folder/file.ext',
            $urlGenerator->generateAbsolutePath('http://localhost', '/folder/file.ext')
        );
    }
}
