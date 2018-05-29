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
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DiscoverCommand extends Command
{
    protected function configure()
    {
        $this->setName('discover')
            ->setDescription('discovers feeds inside a web page')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Please provide the URL'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $output->writeln("<info>Discovering feeds from {$url}</info>");
        $feedIo = Factory::create()->getFeedIo();
        $feeds = $feedIo->discover($url);

        foreach ($feeds as $feed) {
            $output->writeln("<info>found : {$feed}</info>");
        }
    }
}
