<?php

declare(strict_types=1);

namespace ReactInspector\MemoryUsage;

use ReactInspector\CollectorInterface;
use ReactInspector\Config;
use ReactInspector\Measurement;
use ReactInspector\Measurements;
use ReactInspector\Metric;
use ReactInspector\Tag;
use ReactInspector\Tags;
use Rx\Observable;

use function ApiClients\Tools\Rx\observableFromArray;
use function memory_get_peak_usage;
use function memory_get_usage;

use const WyriHaximus\Constants\Boolean\FALSE_;
use const WyriHaximus\Constants\Boolean\TRUE_;

final class MemoryUsageCollector implements CollectorInterface
{
    public function collect(): Observable
    {
        return observableFromArray([
            Metric::create(
                new Config(
                    'reactphp_memory',
                    'gauge',
                    ''
                ),
                new Tags(),
                new Measurements(
                    new Measurement(
                        memory_get_usage(TRUE_),
                        new Tags(
                            new Tag('perspective', 'external'),
                            new Tag('peak', 'false')
                        )
                    ),
                    new Measurement(
                        memory_get_peak_usage(TRUE_),
                        new Tags(
                            new Tag('perspective', 'external'),
                            new Tag('peak', 'true')
                        )
                    ),
                    new Measurement(
                        memory_get_usage(FALSE_),
                        new Tags(
                            new Tag('perspective', 'internal'),
                            new Tag('peak', 'false')
                        )
                    ),
                    new Measurement(
                        memory_get_peak_usage(FALSE_),
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
