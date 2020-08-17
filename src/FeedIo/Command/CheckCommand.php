<?php declare(strict_types=1);

namespace FeedIo\Command;

use FeedIo\Adapter\Guzzle\Client;
use FeedIo\Check\CheckAvailability;
use FeedIo\Check\CheckLastModified;
use FeedIo\Check\CheckPublicIds;
use FeedIo\Check\CheckReadSince;
use FeedIo\Check\Processor;
use FeedIo\Check\Result;
use FeedIo\FeedIo;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class CheckCommand
 * @codeCoverageIgnore
 */
class CheckCommand extends Command
{
    private $ok;

    private $notOk;

    private $feedIo;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $client = new Client(new \GuzzleHttp\Client());
        $this->feedIo = new FeedIo($client, new NullLogger());
    }

    protected function getFeedIo(): FeedIo
    {
        return $this->feedIo;
    }

    protected function configure()
    {
        $this->setName('check')
            ->setDescription('checks if a feed gets correctly updated')
            ->addArgument(
                'url',
                InputArgument::REQUIRED,
                'Please provide an URL or a file to read'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->configureOutput($output);
        $io = new SymfonyStyle($input, $output);
        $urls = $this->getUrls($input);

        $results = [];
        $return = 0;

        foreach ($urls as $url) {
            if (empty($url)) {
                continue;
            }
            $result = $this->runChecks($io, trim($url));
            $results[] = iterator_to_array($this->renderValues($output, $result->toArray()));
            if (!$result->isUpdateable()) {
                $return++;
            }
        }

        $table = new Table($output);
        $table
            ->setHeaders(['URL', 'Accessible', 'readSince', 'Last modified', '# items', 'unique IDs', 'Date Flow'])
            ->setRows($results)
            ->setColumnWidth(0, 48);
        $table->render();

        if ($return > 0) {
            $io->error("Some feeds were marked as not updateable. Two possible explanations: a feed you tried to consumed doesn't match the specification or FeedIo has a bug.");
        }
        return $return;
    }

    protected function getUrls(InputInterface $input): array
    {
        $arg = $input->getArgument('url');
        if (filter_var($arg, FILTER_VALIDATE_URL)) {
            return [$arg];
        }
        if (!file_exists($arg)) {
            throw new \UnexpectedValueException("$arg must contain a valid URL or a file to read");
        }
        $content = file_get_contents($arg);
        return explode("\n", $content);
    }

    protected function renderValues(OutputInterface $output, array $values): \Generator
    {
        foreach ($values as $value) {
            if (is_bool($value)) {
                yield $value ? $this->ok : $this->notOk;
            } elseif ($value === 0 || $value === 'null') {
                yield $output->getFormatter()->format("<ko>$value</ko>");
            } else {
                yield $output->getFormatter()->format("<ok>$value</ok>");
            }
        }
    }

    protected function runChecks(SymfonyStyle $io, string $url): Result
    {
        $io->section("reading {$url}");
        $processor = new Processor($this->getFeedIo());
        $processor
            ->add(new CheckAvailability())
            ->add(new CheckPublicIds())
            ->add(new CheckLastModified())
            ->add(new CheckReadSince());

        return $processor->run($url);
    }

    private function configureOutput(OutputInterface $output): void
    {
        $output->getFormatter()->setStyle(
            'ko',
            new OutputFormatterStyle('red', null, ['bold'])
        );

        $output->getFormatter()->setStyle(
            'ok',
            new OutputFormatterStyle('green', null, ['bold'])
        );

        $this->ok = $output->getFormatter()->format('<ok>OK</ok>');
        $this->notOk = $output->getFormatter()->format("<ko>NOT OK</ko>");
    }
}
