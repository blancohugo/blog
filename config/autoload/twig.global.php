<?php

use BlancoHugo\Blog\Page\Globals\PublishedPagesGlobalProvider;

return [
    'twig' => [
        'provided_globals' => [
            'pages' => PublishedPagesGlobalProvider::class
        ]
    ]
];
