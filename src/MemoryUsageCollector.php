<?php

namespace WyriHaximus\React\Inspector\MemoryUsage;

use React\EventLoop\LoopInterface;
use Rx\ObservableInterface;
use WyriHaximus\React\Inspector\CollectorInterface;
use WyriHaximus\React\Inspector\Metric;
use function ApiClients\Tools\Rx\observableFromArray;

final class MemoryUsageCollector implements CollectorInterface
{
    /**
     * @param LoopInterface $loop
     */
    public function __construct(LoopInterface $loop)
    {
        // void
    }

    public function collect(): ObservableInterface
    {
        return observableFromArray([
            new Metric('memory.external', memory_get_usage(true)),
            new Metric('memory.external_peak', memory_get_peak_usage(true)),
            new Metric('memory.internal', memory_get_usage()),
            new Metric('memory.internal_peak', memory_get_peak_usage()),
        ]);
    }

    public function cancel(): void
    {
        // void
    }
}
