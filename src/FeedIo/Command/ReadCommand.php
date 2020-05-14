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
use FeedIo\Reader\Result\UpdateStats;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ReadCommand
 * @codeCoverageIgnore
 */
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
        $result = $this->readFeed($url);
        $feed = $result->getFeed();

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

        $output->writeln("<info>feed last modified: {$feed->getLastModified()->format(\DATE_ATOM)}</info>");
        $nextUpdate = $result->getNextUpdate();
        $output->writeln("<info>computed next update: {$nextUpdate->format(\DATE_ATOM)}</info>");

        $updateStats = $result->getUpdateStats();

        $output->writeln("minimum interval between items: {$this->formatDateInterval($updateStats->getMinInterval())}");
        $output->writeln("median interval: {$this->formatDateInterval($updateStats->getMedianInterval())}");
        $output->writeln("average interval: {$this->formatDateInterval($updateStats->getAverageInterval())}");
        $output->writeln("maximum interval: {$this->formatDateInterval($updateStats->getMaxInterval())}");

        return 0;
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
     * @param int $interval
     * @return \DateInterval
     */
    protected function formatDateInterval(int $interval): string
    {
        $zero = new \DateTime('@0');
        $diff = $zero->diff(new \DateTime("@{$interval}"));
        return $diff->format('%a days, %h hours, %i minutes, %s seconds');
    }

    /**
     * @param $url
     * @return \FeedIo\Reader\Result
     */
    public function readFeed($url): \FeedIo\Reader\Result
    {
        $feedIo = Factory::create()->getFeedIo();

        return $feedIo->read($url);
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
