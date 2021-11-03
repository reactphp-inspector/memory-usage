<?php

declare(strict_types=1);

namespace ReactInspector\Tests\MemoryUsage;

use ReactInspector\MemoryUsage\MemoryUsageCollector;
use ReactInspector\Metric;
use Rx\React\Promise;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;

use function array_map;
use function Safe\sort;

/**
 * @internal
 */
final class MemoryUsageCollectorTest extends AsyncTestCase
{
    public function testCollectMemoryUsage(): void
    {
        $collector = new MemoryUsageCollector();

        /** @var array<int, Metric> $metrics */
        $metrics = $this->await(Promise::fromObservable($collector->collect()->toArray()));
        self::assertCount(1, $metrics);
        $keys = array_map(static function (Metric $metric): string {
            return $metric->config()->name();
        }, $metrics);
        sort($keys);
        self::assertSame(['reactphp_memory'], $keys);
    }
}
