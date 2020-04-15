<?php declare(strict_types=1);


namespace FeedIo\Command;

/**
 * Class Result
 * @codeCoverageIgnore
 */
class Result
{
    const TEST_UNIQUE_IDS = 'unique_ids';

    const TEST_NORMAL_DATE_FLOW = 'normal_date_flow';

    const TEST_JAN_1970 = 'jan_1970';

    const TEST_1YEAR_OLD = '1year_old';

    const TEST_EMPTY_FUTURE = 'empty_future';

    private $url;

    private $modifiedSince = 'null';

    private $itemCount = 0;

    private $accessible = true;

    private $updateable = true;

    private $tests = [
        self::TEST_UNIQUE_IDS => true,
        self::TEST_NORMAL_DATE_FLOW => true,
        self::TEST_JAN_1970 => true,
        self::TEST_1YEAR_OLD => true,
        self::TEST_EMPTY_FUTURE => true,
    ];

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function setNotAccessible(): self
    {
        $this->accessible = false;
        $this->markAllAsFailed();

        return $this;
    }

    public function isUpdateable(): bool
    {
        return $this->updateable;
    }

    public function setNotUpdateable(): Result
    {
        $this->updateable = false;
        $this->markAllAsFailed();

        return $this;
    }

    public function setModifiedSince(\DateTime $modifiedSince): self
    {
        $this->modifiedSince = $modifiedSince->format(\DATE_ATOM);
        return $this;
    }

    public function setItemCount(int $itemCount): self
    {
        $this->itemCount = $itemCount;
        return $this;
    }

    protected function markAllAsFailed(): void
    {
        foreach ($this->tests as $test => $value) {
            $this->markAsFailed($test);
        }
    }

    public function markAsFailed(string $test): self
    {
        $this->tests[$test] = false;
        return $this;
    }

    public function toArray(): array
    {
        return [
            $this->url,
            $this->accessible,
            $this->updateable,
            $this->modifiedSince,
            $this->itemCount,
            $this->tests[self::TEST_UNIQUE_IDS],
            $this->tests[self::TEST_NORMAL_DATE_FLOW],
            $this->tests[self::TEST_JAN_1970],
            $this->tests[self::TEST_1YEAR_OLD],
            $this->tests[self::TEST_EMPTY_FUTURE],
        ];
    }
}
