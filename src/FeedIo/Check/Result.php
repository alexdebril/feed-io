<?php declare(strict_types=1);


namespace FeedIo\Check;

use DateTime;

/**
 * Class Result
 * @codeCoverageIgnore
 */
class Result
{
    const TEST_UNIQUE_IDS = 'unique_ids';

    const TEST_NORMAL_DATE_FLOW = 'normal_date_flow';

    private $url;

    private $modifiedSince = 'null';

    private $itemDates = [];

    private $itemCount = 0;

    private $accessible = true;

    private $updateable = true;

    private $tests = [
        self::TEST_UNIQUE_IDS => true,
        self::TEST_NORMAL_DATE_FLOW => true,
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

    /**
     * @return int
     */
    public function getItemCount(): int
    {
        return $this->itemCount;
    }

    public function setItemCount(int $itemCount): self
    {
        $this->itemCount = $itemCount;
        return $this;
    }

    /**
     * @return array<DateTime>
     */
    public function getItemDates(): array
    {
        return $this->itemDates;
    }

    /**
     * @param array<DateTime> $itemDates
     */
    public function setItemDates(array $itemDates): void
    {
        $this->itemDates = $itemDates;
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
        ];
    }
}
