<?php

declare(strict_types=1);

return [
    // Provides application-wide services
    'dependencies' => [
        'aliases' => [],
        'invokables' => [],
        'factories'  => [],
        'delegators' => [
            \Mezzio\Application::class => [
                \Mezzio\Container\ApplicationConfigInjectionDelegator::class,
            ],
        ],
    ],
];
