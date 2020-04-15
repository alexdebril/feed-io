<?php declare(strict_types=1);

namespace FeedIo\Command;

use FeedIo\Adapter\Guzzle\Client;
use FeedIo\FeedInterface;
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
            )
        ;
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
            if (! $result->isUpdateable()) {
                $return++;
            }
        }

        $table = new Table($output);
        $table
            ->setHeaders(['URL', 'Accessible', 'readSince', 'Last modified', '# items', 'unique IDs', 'Date Flow', 'Jan 1970', '1 year old', 'Future'])
            ->setRows($results)
            ->setColumnWidth(0, 48)
        ;
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
        if (! file_exists($arg)) {
            throw new \UnexpectedValueException("$arg must contain a valid URL or a file to read");
        }
        $content = file_get_contents($arg);
        return explode("\n", $content);
    }

    protected function renderValues(OutputInterface $output, array $values): \Generator
    {
        foreach ($values as $value) {
            if (is_bool($value)) {
                yield $value ? $this->ok: $this->notOk;
            } elseif ($value === 0 || $value === 'null') {
                yield $output->getFormatter()->format("<ko>$value</ko>");
            } else {
                yield $output->getFormatter()->format("<ok>$value</ok>");
            }
        }
    }

    protected function runChecks(SymfonyStyle $io, string $url): Result
    {
        $result = new Result($url);
        try {
            $io->section("reading {$url}");
            $feed = $this->getFeedIo()->read($url)->getFeed();

            $count = count($feed);
            $this->printResult($io, "the feed has items ($count)", $count > 0);
            if ($count == 0) {
                $result->setNotUpdateable();
                return $result;
            }

            $result->setItemCount($count);
            $firstHitResult = $this->checkFirstHit($io, $feed, $result);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
            $result->setNotAccessible();
            return $result;
        }

        $this->runTimeChecks($io, $url, $result, $firstHitResult);
        unset($feed);
        return $result;
    }

    private function runTimeChecks(SymfonyStyle $io, string $url, Result $result, array $firstHitResult)
    {
        $updateable = $this->checkSecondHit($io, $url, $firstHitResult);
        if (!$updateable) {
            $result->setNotUpdateable();
        }
        $this->printResult($io, "the feed is updateable", $updateable);

        $emptyInTheFuture = $this->checkHitInTheFuture($url);
        if (!$emptyInTheFuture) {
            $result->markAsFailed(Result::TEST_EMPTY_FUTURE);
        }
        $this->printResult($io, "a call in the future is empty as expected", $emptyInTheFuture);

        $dateO = $this->checkHitAtDateO($url);
        if (!$dateO) {
            $result->markAsFailed(Result::TEST_JAN_1970);
        }
        $this->printResult($io, "a call at Jan 1970 is filled as expected", $dateO);

        $OneYearOld = $this->checkHitOneYearOld($url);
        if (!$OneYearOld) {
            $result->markAsFailed(Result::TEST_1YEAR_OLD);
        }
        $this->printResult($io, "a call with modifiedSince = 1yr old is filled", $OneYearOld);
    }

    private function checkFirstHit(SymfonyStyle $io, FeedInterface $feed, Result $result): array
    {
        $lastModifiedDates = [];
        $publicIds = [];
        $result->setModifiedSince($feed->getLastModified());
        /** @var \FeedIo\Feed\ItemInterface $item */
        foreach ($feed as $i => $item) {
            $lastModifiedDates[] = $item->getLastModified();
            $publicIds[] = $item->getPublicId();
        }

        if (! $this->checkPublicIds($publicIds)) {
            $result->markAsFailed(Result::TEST_UNIQUE_IDS);
        }

        sort($lastModifiedDates);
        $first = current($lastModifiedDates);
        $last = end($lastModifiedDates);

        $normalDateFlow = true;
        if (! ($last > $first)) {
            $result->markAsFailed(Result::TEST_NORMAL_DATE_FLOW);
            $normalDateFlow = false;
        }
        $this->printResult($io, "the date flow is normal", $normalDateFlow);

        return [
            'lastModifiedDates' => $lastModifiedDates,
            'normalDateFlow' => $normalDateFlow,
            'publicIds' => $publicIds,
        ];
    }

    private function checkSecondHit(SymfonyStyle $io, string $url, array $firstResult): bool
    {
        $count = count($firstResult['lastModifiedDates']);
        $last = end($firstResult['lastModifiedDates']);
        if ($firstResult['normalDateFlow']) {
            $pick = intval($count / 2);
            $lastModified = $firstResult['lastModifiedDates'][$pick];
        } else {
            $lastModified = $last->sub(new \DateInterval('P1D'));
        }

        $secondFeed = $this->getFeedIo()->readSince($url, $lastModified)->getFeed();

        $count = count($secondFeed);
        $this->printResult($io, "the feed has items on second call ($count)", $count > 0);
        if ($count == 0) {
            return false;
        }

        return true;
    }

    private function checkHitInTheFuture(string $url): bool
    {
        $feed = $this->getFeedIo()->readSince($url, new \DateTime("+1 week"))->getFeed();

        return count($feed) == 0;
    }

    private function checkHitAtDateO(string $url): bool
    {
        $feed = $this->getFeedIo()->readSince($url, new \DateTime("@0"))->getFeed();

        return count($feed) > 0;
    }

    private function checkHitOneYearOld(string $url): bool
    {
        $feed = $this->getFeedIo()->readSince($url, new \DateTime("-1 year"))->getFeed();

        return count($feed) > 0;
    }

    private function checkPublicIds(array $publicIds): bool
    {
        $deduplicated = array_unique($publicIds);
        return count($deduplicated) == count($publicIds);
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

    private function printResult(SymfonyStyle $io, string $message, bool $result): void
    {
        $o = $result ? $this->ok:$this->notOk;
        $t = strlen($message) > 24 ? "\t":"\t\t\t\t";
        $io->text("{$message}: {$t} [{$o}]");
    }
}
