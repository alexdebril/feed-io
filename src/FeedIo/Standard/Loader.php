<?php declare(strict_types=1);

/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Standard;

use FeedIo\Rule\DateTimeBuilderInterface;

class Loader
{
    public function getCommonStandards(DateTimeBuilderInterface $builder) : array
    {
        return [
            'json' => new Json($builder),
            'atom' => new Atom($builder),
            'rss' => new Rss($builder),
            'rdf' => new Rdf($builder),
        ];
    }
}
