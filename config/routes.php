<?php
return [
    'paths' => [
        base_path('routes/web.php'),
        base_path('routes/api.php'),
        // add any other route files here if you want
    ],

    'prefixes' => [
        'api' => 'api',
    ],

    'middleware_groups' => [
        'web' => ['web'],
        'api' => ['api'],
    ],
];
