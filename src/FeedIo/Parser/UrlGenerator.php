<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Parser;

class UrlGenerator
{

    /**
     * @param string $host
     * @param string $path
     * @return string
     */
    public function getAbsolutePath(string $path, string $host = null): string
    {
        if (! parse_url($path, PHP_URL_HOST) && $host) {
            return $this->generateAbsolutePath($host, $path);
        }
        return $path;
    }

    /**
     * @param string $host
     * @param string $path
     * @return string
     */
    public function generateAbsolutePath(string $host, string $path): string
    {
        $path = substr($path, 0, 1) == '/' ? $path:"/{$path}";
        return $host . $path;
    }
}
