<?php declare(strict_types=1);

namespace ReactInspector\Tests\MemoryUsage;

use ReactInspector\MemoryUsage\MemoryUsageCollector;
use ReactInspector\Metric;
use Rx\React\Promise;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;

/**
 * @internal
 */
final class MemoryUsageCollectorTest extends AsyncTestCase
{
    public function testCollectMemoryUsage(): void
    {
        $collector = new MemoryUsageCollector();

        /** @var Metric $metric */
        $metrics = $this->await(Promise::fromObservable($collector->collect()->toArray()));
        self::assertCount(1, $metrics);
        $keys = \array_map(function (Metric $metric) {
            return $metric->name();
        }, $metrics);
        \sort($keys);
        self::assertSame([
            'memory',
        ], $keys);
    }
}
