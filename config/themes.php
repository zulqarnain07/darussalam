<?php

return [
    'default' => 'dvelocity',
    'themes' => [
        'default' => [
            'views_path' => 'resources/themes/default/views',
            'assets_path' => 'public/themes/default/assets',
            'name' => 'Default',
        ],
        'dvelocity' => [
            'views_path' => 'resources/themes/dvelocity/views',
            'assets_path' => 'public/themes/dvelocity/assets',
            'name' => 'dvelocity',
            'parent' => 'default',
        ],
        'darusalam' => [
            'views_path' => 'resources/themes/darusalam/views',
            'assets_path' => 'public/themes/darusalam/assets',
            'name' => 'Darusalam',
            'parent' => 'default',
        ],
    ],
    'admin-default' => 'default',
    'admin-themes' => [
        'default' => [
            'views_path' => 'resources/admin-themes/default/views',
            'assets_path' => 'public/admin-themes/default/assets',
            'name' => 'Default',
        ],
    ],
];
?>