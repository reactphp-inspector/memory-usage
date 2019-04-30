<?php declare(strict_types=1);

namespace ReactInspector\MemoryUsage;

use function ApiClients\Tools\Rx\observableFromArray;
use React\EventLoop\LoopInterface;
use ReactInspector\CollectorInterface;
use ReactInspector\Metric;
use Rx\ObservableInterface;

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
            new Metric('memory.external', \memory_get_usage(true)),
            new Metric('memory.external_peak', \memory_get_peak_usage(true)),
            new Metric('memory.internal', \memory_get_usage()),
            new Metric('memory.internal_peak', \memory_get_peak_usage()),
        ]);
    }

    public function cancel(): void
    {
        // void
    }
}
