<?php declare(strict_types=1);


namespace FeedIo\Check;

use FeedIo\Feed;
use FeedIo\FeedIo;

/**
 * Class Processor
 * @codeCoverageIgnore
 */
class Processor
{

    /**
     * Checks to perform
     *
     * @var array<CheckInterface>
     */
    protected $checks = [];

    /**
     * @var FeedIo
     */
    protected $feedIo;

    /**
     * @param FeedIo $feedIo
     */
    public function __construct(FeedIo $feedIo)
    {
        $this->feedIo = $feedIo;
    }

    /**
     * @param CheckInterface $check
     * @return $this
     */
    public function add(CheckInterface $check): Processor
    {
        $this->checks[] = $check;

        return $this;
    }

    /**
     * @param string $url
     * @return Result
     */
    public function run(string $url): Result
    {
        $result = new Result($url);
        $feed = (new Feed())->setUrl($url);
        try {
            /** @var CheckInterface $check */
            foreach ($this->checks as $check) {
                if (!$check->perform($this->feedIo, $feed, $result)) {
                    $result->setNotAccessible();
                    return $result;
                }
            }
        } catch (\Throwable $exception) {
            $result->setNotAccessible();
            return $result;
        }


        return $result;
    }
}
