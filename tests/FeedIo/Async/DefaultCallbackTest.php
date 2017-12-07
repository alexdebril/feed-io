<?php

namespace FeedIo\Async;

use Psr\Log\LoggerInterface;
use \PHPUnit\Framework\TestCase;

class DefautlCallbackTest extends TestCase
{
    public function testProcess()
    {
        $logger = $this->getMockForAbstractClass('\Psr\Log\LoggerInterface');
        $logger->expects($this->once())->method('info')->will($this->returnValue(true));

        $result = $this->createMock('\FeedIo\Reader\Result');
        $result->expects($this->once())->method('getUrl')->will($this->returnValue('http://'));
        $callback = new DefaultCallback($logger);
        $callback->process($result);
    }

    public function testHandleError()
    {
        $logger = $this->getMockForAbstractClass('\Psr\Log\LoggerInterface');
        $logger->expects($this->once())->method('warning')->will($this->returnValue(true));

        $callback = new DefaultCallback($logger);
        $callback->handleError(new Request('test'), new \Exception);
    }
}
