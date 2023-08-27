<?php

declare(strict_types=1);

namespace Riot\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Riot\Exception\RateLimitExceededException;

final class RateLimitExceededExceptionTest extends TestCase
{
    public function testCreateFromResponseReturnsProperObject(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $response->expects(self::exactly(7))
            ->method('getHeader')
            ->withConsecutive(
                ['x-riot-edge-trace-id'],
                ['retry-after'],
                ['x-rate-limit-type'],
                ['x-app-rate-limit'],
                ['x-app-rate-limit-count'],
                ['x-method-rate-limit'],
                ['x-method-rate-limit-count'],
            )
            ->willReturn(
                ['trace-id'],
                ['1'],
                ['application'],
                ['20:1,100:120'],
                ['13:1,101:120'],
                ['500:10'],
                ['101:10'],
            )
        ;

        $object = RateLimitExceededException::createFromResponse('message', $response);
        self::assertSame('trace-id', $object->getEdgeTraceId());
        self::assertSame(1, $object->getRetryAfter());
        self::assertSame('application', $object->getRateLimitType());
        self::assertSame('20:1,100:120', $object->getAppRateLimit());
        self::assertSame('13:1,101:120', $object->getAppRateLimitCount());
        self::assertSame('500:10', $object->getMethodRateLimit());
        self::assertSame('101:10', $object->getMethodRateLimitCount());
    }
}
