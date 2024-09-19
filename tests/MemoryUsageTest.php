<?php

declare(strict_types=1);

namespace ReactInspector\Tests\MemoryUsage;

use ReactInspector\MemoryUsage\MemoryUsage;
use WyriHaximus\AsyncTestUtilities\AsyncTestCase;
use WyriHaximus\Metrics\Configuration;
use WyriHaximus\Metrics\InMemory\Registry;
use WyriHaximus\Metrics\Printer\Prometheus;

use function React\Async\await;
use function React\Promise\Timer\sleep;

final class MemoryUsageTest extends AsyncTestCase
{
    /** @test */
    public function collectMemoryUsage(): void
    {
        $registry  = new Registry(Configuration::create());
        $collector = new MemoryUsage();
        $collector->register($registry, 0.1);
        await(sleep(0.2));
        $collector->unregister($registry);

        self::assertStringContainsString('reactphp_memory{peak="false",perspective="external"}', $registry->print(new Prometheus()));
        self::assertStringContainsString('reactphp_memory{peak="true",perspective="external"}', $registry->print(new Prometheus()));
        self::assertStringContainsString('reactphp_memory{peak="false",perspective="internal"}', $registry->print(new Prometheus()));
        self::assertStringContainsString('reactphp_memory{peak="true",perspective="internal"}', $registry->print(new Prometheus()));
    }
}
