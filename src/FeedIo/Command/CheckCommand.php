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
use FeedIo\Feed\ItemInterface;
use FeedIo\FeedInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{
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

        $feedIo = Factory::create()->getFeedIo();
        $feed = $feedIo->read($url)->getFeed();

        $output->writeln("<info>first access to {$feed->getTitle()}</info>");

        $failures = 0;

        $count = count($feed);
        if( $count == 0) {
            $failures++;
            $output->writeln("<error>empty feed</error>");
        }

        $output->writeln("<info>found {$count} items</info>");

        $lastModifiedDates = [];
        /** @var \FeedIo\Feed\ItemInterface $item */
        foreach ($feed as $i => $item) {
            $lastModifiedDates[] = $item->getLastModified();
        }

        sort($lastModifiedDates);

        $first = current($lastModifiedDates);

        /** @var \DateTime $last */
        $last = end($lastModifiedDates);

        $normalDateFlow = true;
        if ($last > $first) {
            $output->writeln("<info>first item was published on {$first->format(\DateTime::ATOM)}</info>");
            $output->writeln("<info>last item was published on {$last->format(\DateTime::ATOM)}</info>");
        } else {
            $output->writeln("<warn>All items have the same date</warn>");
            $normalDateFlow = false;
        }

        if ($normalDateFlow) {
            $pick = intval($count / 2);
            $lastModified = $lastModifiedDates[$pick];
        } else {
            $lastModified = $last->sub(new \DateInterval('-1 days'));
        }

        $secondHit = $feedIo->readSince($url, $lastModified)->getFeed();

        $count = count($secondHit);
        if( $count == 0) {
            $failures++;
            $output->writeln("<error>empty feed</error>");
        }

        $output->writeln("<info>found {$count} items</info>");
        return $failures;
    }

    private function configureOutput(OutputInterface $output): void
    {
        $outputStyle = new OutputFormatterStyle('black', 'magenta', ['bold']);
        $output->getFormatter()->setStyle('warn', $outputStyle);
    }

}
