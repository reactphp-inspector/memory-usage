<?php declare(strict_types=1);

namespace ReactInspector\MemoryUsage;

use function ApiClients\Tools\Rx\observableFromArray;
use ReactInspector\CollectorInterface;
use ReactInspector\Config;
use ReactInspector\Measurement;
use ReactInspector\Measurements;
use ReactInspector\Metric;
use ReactInspector\Tag;
use ReactInspector\Tags;
use Rx\Observable;

final class MemoryUsageCollector implements CollectorInterface
{
    public function collect(): Observable
    {
        return observableFromArray([
            new Metric(
                new Config(
                    'reactphp_memory',
                    'gauge',
                    ''
                ),
                new Tags(),
                new Measurements(
                    new Measurement(
                        \memory_get_usage(true),
                        new Tags(
                            new Tag('perspective', 'external'),
                            new Tag('peak', 'false')
                        )
                    ),
                    new Measurement(
                        \memory_get_peak_usage(true),
                        new Tags(
                            new Tag('perspective', 'external'),
                            new Tag('peak', 'true')
                        )
                    ),
                    new Measurement(
                        \memory_get_usage(false),
                        new Tags(
                            new Tag('perspective', 'internal'),
                            new Tag('peak', 'false')
                        )
                    ),
                    new Measurement(
                        \memory_get_peak_usage(false),
                        new Tags(
                            new Tag('perspective', 'internal'),
                            new Tag('peak', 'true')
                        )
                    ),
                )
            ),
        ]);
    }

    public function cancel(): void
    {
        // void
    }
}
