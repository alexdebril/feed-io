<?php declare(strict_types=1);

namespace FeedIo\Command;

use FeedIo\Factory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DiscoverCommand
 * @codeCoverageIgnore
 */
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

        return 0;
    }
}
