<?php declare(strict_types=1);
/*
 * This file is part of the feed-io package.
 *
 * (c) Alexandre Debril <alex.debril@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FeedIo\Command;

use FeedIo\Factory;
use FeedIo\Feed\ItemInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReadCommand extends Command
{
    protected function configure()
    {
        $this->setName('read')
            ->setDescription('reads a feed')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Please provide the feed\' URL'
            )
            ->addOption('count', 'c', InputOption::VALUE_OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $feed = $this->readFeed($url);

        $output->writeln("<info>{$feed->getTitle()}</info>");

        $limit = $this->getLimit($input);

        /** @var \FeedIo\Feed\ItemInterface $item */
        foreach ($feed as $i => $item) {
            $lastModified = $item->getLastModified() ?: new \DateTime();
            $output->writeln("<info>{$lastModified->format(\DateTime::ATOM)} : {$item->getTitle()}</info>");
            $output->writeln("{$item->getDescription()}");

            $this->handleMedias($item, $output);
            if (! is_null($limit) && $limit === $i+1) {
                break;
            }
        }
    }

    /**
     * @param ItemInterface $item
     * @param OutputInterface $output
     */
    protected function handleMedias(ItemInterface $item, OutputInterface $output)
    {
        /** @var \FeedIo\Feed\Item\MediaInterface $media */
        foreach ($item->getMedias() as $media) {
            $output->writeln("media found : {$media->getUrl()}");
        }
    }

    /**
     * @param string $url
     * @return \FeedIo\FeedInterface
     */
    public function readFeed($url)
    {
        $feedIo = Factory::create()->getFeedIo();

        return $feedIo->read($url)->getFeed();
    }

    /**
     * @param InputInterface $input
     * @return int|null
     */
    public function getLimit(InputInterface $input)
    {
        if ($input->hasOption('count')) {
            return intval($input->getOption('count'));
        }

        return null;
    }
}
