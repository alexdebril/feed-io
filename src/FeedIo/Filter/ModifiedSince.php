<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Filter;

use FeedIo\Feed\ItemInterface;
use FeedIo\FilterInterface;

class ModifiedSince implements FilterInterface
{
    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * ModifiedSince constructor.
     * @param \DateTime $date
     */
    public function __construct(\DateTime $date)
    {
        $this->date = $date;
    }


    /**
     * @param  ItemInterface $item
     * @return bool
     */
    public function isValid(ItemInterface $item) : bool
    {
        if ($item->getLastModified() instanceof \DateTime) {
            return $item->getLastModified() > $this->date;
        }

        return false;
    }
}
