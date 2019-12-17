<?php declare(strict_types=1);

namespace ReactInspector\MemoryUsage;

use function ApiClients\Tools\Rx\observableFromArray;
use React\EventLoop\LoopInterface;
use ReactInspector\CollectorInterface;
use ReactInspector\Measurement;
use ReactInspector\Metric;
use ReactInspector\Tag;
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
            new Metric(
                'reactphp_memory',
                [],
                [
                    new Measurement(
                        \memory_get_usage(true),
                        new Tag('perspective', 'external'),
                        new Tag('peak', 'false')
                    ),
                    new Measurement(
                        \memory_get_peak_usage(true),
                        new Tag('perspective', 'external'),
                        new Tag('peak', 'true')
                    ),
                    new Measurement(
                        \memory_get_usage(false),
                        new Tag('perspective', 'internal'),
                        new Tag('peak', 'false')
                    ),
                    new Measurement(
                        \memory_get_peak_usage(false),
                        new Tag('perspective', 'internal'),
                        new Tag('peak', 'true')
                    ),
                ]
            ),
        ]);
    }

    public function cancel(): void
    {
        // void
    }
}
