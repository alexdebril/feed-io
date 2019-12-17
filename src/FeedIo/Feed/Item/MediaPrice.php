<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Feed\Item;

abstract class MediaPriceType extends MediaConstant
{
    const Free = 1;
    const Rent = 2;
    const Purchase = 3;
    const Package = 4;
    const Subscription = 5;

    const VALUES = array(
        "free" => MediaPriceType::Free,
        "rent" => MediaPriceType::Rent,
        "purchase" => MediaPriceType::Purchase,
        "package" => MediaPriceType::Package,
        "subscription" => MediaPriceType::Subscription,
    );
}


class MediaPrice
{
    /**
     * @var int
     */
    protected $type;

    /**
     * @var float
     */
    protected $value;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @param  int $type
     * @return MediaPrice
     */
    public function setType(? int $type) : MediaPrice
    {
        $this->type = $type;
        return $this;
    }

    public function getType() : ? int
    {
        return $this->type;
    }

    /**
     * @param  float $value
     * @return MediaPrice
     */
    public function setValue(? float $value) : MediaPrice
    {
        $this->value = $value;
        return $this;
    }

    public function getValue() : ? float
    {
        return $this->value;
    }

    /**
     * @param  string|null currency
     * @return MediaPrice
     */
    public function setCurrency(? string $currency) : MediaPrice
    {
        $this->currency = $currency;
        return $this;
    }

    public function getCurrency() : ? string
    {
        return $this->currency;
    }
}
