<?php

/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$loader = require __DIR__."/../vendor/autoload.php";
$loader->addPsr4('FeedIo\\', __DIR__.'/FeedIo');

date_default_timezone_set('UTC');
