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


class OptionalFields implements OptionalFieldsInterface
{
    /**
     * @var array
     */
    protected $data = array();

    /**
     * @return array
     */
    public function getFields()
    {
        return array_keys($this->data);
    }

    /**
     * @param $name
     * @return boolean
     */
    public function has($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * @param $name
     * @return string
     */
    public function get($name)
    {
        if ( $this->has($name) ) {
            return $name;
        }

        return null;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

} 