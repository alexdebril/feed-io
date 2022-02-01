<?php

declare(strict_types=1);

namespace FeedIo\Feed\Node;

use FeedIo\Feed\ElementsAwareInterface;
use FeedIo\Feed\ElementsAwareTrait;

class Element implements ElementInterface, ElementsAwareInterface
{
    use ElementsAwareTrait;

    protected ?string $name = null;

    protected ?string $value = null;

    protected array $attributes = [];

    public function __construct()
    {
        $this->initElements();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string $name
     * @return ElementInterface
     */
    public function setName(string $name): ElementInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return ElementInterface
     */
    public function setValue(string $value = null): ElementInterface
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param string $name
     * @return null|string
     */
    public function getAttribute(string $name): ?string
    {
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * @return iterable
     */
    public function getAttributes(): iterable
    {
        return $this->attributes;
    }

    /**
     * @param string $name
     * @param string|null $value
     * @return ElementInterface
     */
    public function setAttribute(string $name, string $value = null): ElementInterface
    {
        $this->attributes[$name] = $value;

        return $this;
    }
}
