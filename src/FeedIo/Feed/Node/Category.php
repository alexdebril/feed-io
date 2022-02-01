<?php

declare(strict_types=1);

namespace FeedIo\Feed\Node;

class Category implements CategoryInterface
{
    protected ?string $term = null;

    protected ?string $scheme = null;

    protected ?string $label = null;

    /**
     * @return null|string
     */
    public function getTerm(): ?string
    {
        return $this->term;
    }

    /**
     * @param string|null $term
     * @return CategoryInterface
     */
    public function setTerm(string $term = null): CategoryInterface
    {
        $this->term = $term;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    /**
     * @param string|null $scheme
     * @return CategoryInterface
     */
    public function setScheme(string $scheme = null): CategoryInterface
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     * @return CategoryInterface
     */
    public function setLabel(string $label = null): CategoryInterface
    {
        $this->label = $label;

        return $this;
    }
}
