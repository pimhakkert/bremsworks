<?php

/**
 * Returns the importmap for this application.
 *
 * - "path" is a path inside the asset mapper system. Use the
 *     "debug:asset-map" command to see the full list of paths.
 *
 * - "entrypoint" (JavaScript only) set to true for any module that will
 *     be used as an "entrypoint" (and passed to the importmap() Twig function).
 *
 * The "importmap:require" command can be used to add new entries to this file.
 */
return [
    'admin' => [
        'path' => './assets/admin/app.js',
        'entrypoint' => true,
    ],
    'alpinejs' => [
        'version' => '3.14.7',
    ],
    'chart.js' => [
        'version' => '4.4.1',
    ],
    '@kurkle/color' => [
        'version' => '0.3.2',
    ],
    'moment' => [
        'version' => '2.30.1',
    ],
    'chartjs-adapter-moment' => [
        'version' => '1.0.1',
    ],
    'flatpickr' => [
        'version' => '4.6.13',
    ],
    'flatpickr/dist/flatpickr.min.css' => [
        'version' => '4.6.13',
        'type' => 'css',
    ],
    'chart.js/auto' => [
        'version' => '4.4.7',
    ],
    'date-fns' => [
        'version' => '3.3.1',
    ],
    'chartjs-adapter-date-fns' => [
        'version' => '3.0.0',
    ],
    'tom-select' => [
        'version' => '2.4.1',
    ],
    '@orchidjs/sifter' => [
        'version' => '1.1.0',
    ],
    '@orchidjs/unicode-variants' => [
        'version' => '1.1.2',
    ],
    'tom-select/dist/css/tom-select.default.min.css' => [
        'version' => '2.4.1',
        'type' => 'css',
    ],
];
