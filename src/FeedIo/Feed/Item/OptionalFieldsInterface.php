<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;


interface OptionalFieldsInterface
{
    /**
     * @return array
     */
    public function getFields();

    /**
     * @param $name
     * @return boolean
     */
    public function has($name);

    /**
     * @param $name
     * @return string
     */
    public function get($name);

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function set($name, $value);
}
