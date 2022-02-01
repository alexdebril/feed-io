<?php

declare(strict_types=1);

namespace FeedIo;

use ArrayIterator;
use FeedIo\Rule\OptionalField;

class RuleSet
{
    protected ArrayIterator $rules;

    protected array $aliases = array();

    protected RuleAbstract $default;

    public function __construct(RuleAbstract $default = null)
    {
        $this->rules = new \ArrayIterator(array());
        $this->default = is_null($default) ? new OptionalField() : $default;
    }

    public function getDefault(): RuleAbstract
    {
        return $this->default;
    }

    public function getRules(): array
    {
        return $this->rules->getArrayCopy();
    }

    public function add(RuleAbstract $rule, array $aliases = array()): RuleSet
    {
        $this->rules->offsetSet(strtolower($rule->getNodeName()), $rule);
        $this->addAliases($rule->getNodeName(), $aliases);

        return $this;
    }

    public function addAliases(string $name, array $aliases): RuleSet
    {
        foreach ($aliases as $alias) {
            $this->aliases[strtolower($alias)] = strtolower($name);
        }

        return $this;
    }

    public function get(string $name): RuleAbstract
    {
        $name = $this->getNameForAlias(strtolower($name));
        if ($this->rules->offsetExists($name)) {
            return $this->rules->offsetGet($name);
        }

        return $this->default;
    }

    public function getNameForAlias(string $alias): string
    {
        if (array_key_exists($alias, $this->aliases)) {
            return $this->aliases[$alias];
        }

        return $alias;
    }
}
