<?php

declare(strict_types=1);

namespace ReactInspector\MemoryUsage;

use React\EventLoop\Loop;
use React\EventLoop\TimerInterface;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Registry;

use function memory_get_peak_usage;
use function memory_get_usage;
use function spl_object_id;

final class MemoryUsage
{
    private const DEFAULT_INTERVAL     = 1.337;
    private const PERSPECTIVE_INTERNAL = false;
    private const PERSPECTIVE_EXTERNAL = true;

    /** @var array<int, TimerInterface> */
    private array $registries = [];

    public function register(Registry $registry, float $interval = self::DEFAULT_INTERVAL): void
    {
        $gauges                                     = $registry->gauge('reactphp_memory', 'Process Memmory usage', new Label\Name('perspective'), new Label\Name('peak'));
        $this->registries[spl_object_id($registry)] = Loop::addPeriodicTimer($interval, static function () use ($gauges): void {
            $gauges->gauge(new Label('perspective', 'external'), new Label('peak', 'false'))->set(memory_get_usage(self::PERSPECTIVE_EXTERNAL));
            $gauges->gauge(new Label('perspective', 'external'), new Label('peak', 'true'))->set(memory_get_peak_usage(self::PERSPECTIVE_EXTERNAL));
            $gauges->gauge(new Label('perspective', 'internal'), new Label('peak', 'false'))->set(memory_get_usage(self::PERSPECTIVE_INTERNAL));
            $gauges->gauge(new Label('perspective', 'internal'), new Label('peak', 'true'))->set(memory_get_peak_usage(self::PERSPECTIVE_INTERNAL));
        });
    }

    public function unregister(Registry $registry): void
    {
        Loop::cancelTimer($this->registries[spl_object_id($registry)]);
        unset($this->registries[spl_object_id($registry)]);
    }
}
