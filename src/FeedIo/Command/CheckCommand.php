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

use FeedIo\Command\Check\CheckerAbstract;
use FeedIo\Command\Check\CountChecker;
use FeedIo\Command\Check\HistoryChecker;
use FeedIo\Factory;
use FeedIo\Feed;
use FeedIo\Feed\ItemInterface;
use FeedIo\FeedInterface;
use FeedIo\FeedIo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{

    const UPDATE_PROBLEM = "<warn>Issues found on readSince. Please consider filtering this feed using its public ids</warn>";

    protected function configure()
    {
        $this->setName('check')
            ->setDescription('checks if a feed gets correctly updated')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Please provide the feed\' URL'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configureOutput($output);
        $url = $input->getArgument('url');

        if( ! $this->runChecks($output, $url) ) {
            $output->writeln("<error>This feed cannot be properly used by feed-io. Please read the above error message and if you think it's a mistake, feel free to submit an issue on Github</error>");
            return 1;
        }

        $output->writeln("<success>This feed can be consumed by feed-io</success>");
        return 0;
    }

    protected function runChecks(OutputInterface $output, string $url): bool
    {
        $feedIo = Factory::create()->getFeedIo();
        $feed = $feedIo->read($url)->getFeed();

        $output->writeln("<info>first access to {$feed->getTitle()}</info>");

        $count = count($feed);
        if ($count == 0) {
            $output->writeln("<error>empty feed</error>");
            return false;
        }

        $output->writeln("<info>found {$count} items</info>");

        $firstHitResult = $this->checkFirstHit($output, $feed);

        $updateStatus = true;
        if ($this->checkSecondHit($output, $feedIo, $url, $firstHitResult)) {
            $output->writeln("<info>readSince works fine</info>");
        } else {
            $updateStatus = false;
            $output->writeln(self::UPDATE_PROBLEM);
        }

        if ($this->checkHitInTheFuture($feedIo, $url)) {
            $output->writeln("<info>a call in the future is empty as expected</info>");
        } else {
            $updateStatus = false;
            $output->writeln(self::UPDATE_PROBLEM);
        }

        return $updateStatus;
    }

    private function checkFirstHit(OutputInterface $output, FeedInterface $feed): array
    {
        $lastModifiedDates = [];
        $publicIds = [];
        /** @var \FeedIo\Feed\ItemInterface $item */
        foreach ($feed as $i => $item) {
            $lastModifiedDates[] = $item->getLastModified();
            $publicIds[] = $item->getPublicId();
        }

        if (! $this->checkPublicIds($publicIds)) {
            $output->writeln("<warn>duplicated publicIds found</warn>");
        }

        sort($lastModifiedDates);
        $first = current($lastModifiedDates);
        $last = end($lastModifiedDates);

        $normalDateFlow = true;
        if ($last > $first) {
            $output->writeln("<info>first item was published on {$first->format(\DateTime::ATOM)}</info>");
            $output->writeln("<info>last item was published on {$last->format(\DateTime::ATOM)}</info>");
        } else {
            $output->writeln("<warn>All items have the same date</warn>");
            $normalDateFlow = false;
        }

        return [
            'lastModifiedDates' => $lastModifiedDates,
            'normalDateFlow' => $normalDateFlow,
            'publicIds' => $publicIds,
        ];
    }

    private function checkSecondHit(OutputInterface $output, FeedIo $feedIo, string $url, array $firstResult): bool
    {
        $count = count($firstResult['lastModifiedDates']);
        $last = end($firstResult['lastModifiedDates']);
        if ($firstResult['normalDateFlow']) {
            $pick = intval($count / 2);
            $lastModified = $firstResult['lastModifiedDates'][$pick];
        } else {
            $lastModified = $last->sub(new \DateInterval('P1D'));
        }

        $secondFeed = $feedIo->readSince($url, $lastModified)->getFeed();

        $count = count($secondFeed);
        if ($count == 0) {
            $output->writeln("<error>The feed is empty on second call, it should have a partial result</error>");
            return false;
        }

        $output->writeln("<info>found {$count} items on second call</info>");
        /** @var \FeedIo\Feed\ItemInterface $item */
        foreach ($secondFeed as $item) {
            if(! in_array($item->getPublicId(), $firstResult['publicIds'])) {
                $output->writeln("<warn>Unknown public ID detected, you should retry to see if it was just a new item published during the check process</warn>");
            }
        }

        return true;
    }

    private function checkHitInTheFuture( FeedIo $feedIo, string $url): bool
    {
        $feed = $feedIo->readSince($url, new \DateTime("+1 week"))->getFeed();

        return count($feed) == 0;
    }

    private function checkPublicIds(array $publicIds): bool
    {
        $deduplicated = array_unique($publicIds);
        return count($deduplicated) == count($publicIds);
    }

    private function configureOutput(OutputInterface $output): void
    {
        $output->getFormatter()->setStyle(
            'warn',
            new OutputFormatterStyle('black', 'magenta', ['bold'])
        );

        $output->getFormatter()->setStyle(
            'success',
            new OutputFormatterStyle('black', 'green', ['bold'])
        );
    }
}
