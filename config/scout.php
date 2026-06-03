<?php

return [
    'driver' => env('SCOUT_DRIVER', 'elasticsearch'),

    'prefix' => env('SCOUT_PREFIX', ''),

    'queue' => env('SCOUT_QUEUE', true),

    'after_commit' => false,

    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],

    'soft_delete' => false,

    'identify' => env('SCOUT_IDENTIFY', false),

    'elasticsearch' => [
        'hosts' => explode(',', env('ELASTICSEARCH_HOSTS', 'http://localhost:9200')),
        'user' => env('ELASTICSEARCH_USER', null),
        'pass' => env('ELASTICSEARCH_PASS', null),
        'analyser' => env('ELASTICSEARCH_ANALYSER', 'standard'),
        'index' => env('ELASTICSEARCH_INDEX', 'marketplace'),
    ],

    'algolia' => [
        'id' => env('ALGOLIA_APP_ID', ''),
        'secret' => env('ALGOLIA_SECRET', ''),
    ],

    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://localhost:7700'),
        'key' => env('MEILISEARCH_KEY', null),
    ],

    'tntsearch' => [
        'storage' => storage_path('search'),
        'active' => true,
        'search_length' => 3,
        'case_sensitive' => false,
        'fuzziness' => env('TNTSEARCH_FUZZINESS', true),
        'fuzzy_distance' => env('TNTSEARCH_FUZZY_DISTANCE', 2),
        'asYouType' => false,
        'maxDocs' => 10000,
    ],
];
