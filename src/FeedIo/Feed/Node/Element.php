<?php declare(strict_types=1);

namespace FeedIo\Feed\Node;

use FeedIo\Feed\ElementsAwareInterface;
use FeedIo\Feed\ElementsAwareTrait;

class Element implements ElementInterface, ElementsAwareInterface
{
    use ElementsAwareTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $attributes = array();

    public function __construct()
    {
        $this->initElements();
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return ElementInterface
     */
    public function setName(string $name) : ElementInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getValue() : ? string
    {
        return $this->value;
    }

    /**
     * @param  string $value
     * @return ElementInterface
     */
    public function setValue(string $value = null) : ElementInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function getAttribute(string $name) : ? string
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * @return iterable
     */
    public function getAttributes() : iterable
    {
        return $this->attributes;
    }

    /**
     * @param  string $name
     * @param  string $value
     * @return ElementInterface
     */
    public function setAttribute(string $name, string $value = null) : ElementInterface
    {
        $this->attributes[$name] = $value;

        return $this;
    }
}
