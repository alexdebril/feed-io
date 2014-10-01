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

use FeedIo\Parser\Rule\OptionalField;

class RuleSet
{
    /**
     * @var \ArrayIterator
     */
    protected $rules;

    /**
     * @var OptionalField
     */
    protected $default;

    public function __construct()
    {
        $this->rules = new \ArrayIterator(array());
        $this->default = new OptionalField();
    }

    /**
     * @param RuleAbstract $rule
     * @return $this
     */
    public function add(RuleAbstract $rule)
    {
        $this->rules->offsetSet(strtolower($rule->getNodeName()), $rule);

        return $this;
    }

    /**
     * @param $name
     * @return RuleAbstract
     * @throws NotFoundException
     */
    public function get($name)
    {
        $name = strtolower($name);
        if ( $this->rules->offsetExists($name) ) {
            return $this->rules->offsetGet($name);
        }

       return $this->default;
    }
} 