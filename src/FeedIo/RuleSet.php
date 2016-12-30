<?php
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo;

use FeedIo\Rule\OptionalField;

class RuleSet
{
    /**
     * @var \ArrayIterator
     */
    protected $rules;

    /**
     * @var array
     */
    protected $aliases = array();

    /**
     * @var RuleAbstract
     */
    protected $default;

    /**
     * @param RuleAbstract $default default rule
     */
    public function __construct(RuleAbstract $default = null)
    {
        $this->rules = new \ArrayIterator(array());
        $this->default = is_null($default) ? new OptionalField() : $default;
    }

    /**
     * @return RuleAbstract
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules->getArrayCopy();
    }

    /**
     * @param  RuleAbstract $rule
     * @return $this
     */
    public function add(RuleAbstract $rule, array $aliases = array())
    {
        $this->rules->offsetSet(strtolower($rule->getNodeName()), $rule);
        $this->addAliases($rule->getNodeName(), $aliases);

        return $this;
    }

    /**
     * @param  string $name
     * @param  array  $aliases
     * @return $this
     */
    public function addAliases($name, array $aliases)
    {
        foreach ($aliases as $alias) {
            $this->aliases[strtolower($alias)] = strtolower($name);
        }

        return $this;
    }

    /**
     * @param  string            $name
     * @return RuleAbstract
     * @throws NotFoundException
     */
    public function get($name)
    {
        $name = $this->getNameForAlias(strtolower($name));
        if ($this->rules->offsetExists($name)) {
            return $this->rules->offsetGet($name);
        }

        return $this->default;
    }

    /**
     * @param string $alias
     */
    public function getNameForAlias($alias)
    {
        if (array_key_exists($alias, $this->aliases)) {
            return $this->aliases[$alias];
        }

        return $alias;
    }
}
