<?php

declare(strict_types=1);

namespace FeedIo\Feed\Node;

use PharIo\Manifest\ElementCollection;

/**
 * Describe an Element instance
 *
 * $name matches the node's tag name
 * $value matches the node's content
 * each attribute matches an attribute of the node
 *
 * for example, to represent this XML node
 *
 * <media lenght="45668" type="audio/mpeg">http://example.org/some-sound.mp3</media>
 *
 * you must set the ElementInstance's properties this way
 *
 * <code>
 * $item->setName('media');
 * $item->setValue('http://example.org/some-sound.mp3');
 * $item->setAttribute('lenght', 45668);
 * $item->setAttribute('type', 'audio/mpeg');
 *
 * </code>
 */
interface ElementInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param  string $name
     * @return ElementInterface
     */
    public function setName(string $name): ElementInterface;

    /**
     * @return string
     */
    public function getValue(): ?string;

    /**
     * @param  string $value
     * @return ElementInterface
     */
    public function setValue(string $value = null): ElementInterface;

    /**
     * @param  string $name
     * @return string
     */
    public function getAttribute(string $name): ?string;

    /**
     * @return iterable
     */
    public function getAttributes(): iterable;

    /**
     * @param  string $name
     * @param  string $value
     * @return ElementInterface
     */
    public function setAttribute(string $name, string $value = null): ElementInterface;
}
