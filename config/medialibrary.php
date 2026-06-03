<?php

return [
    'disk_name' => env('MEDIA_DISK', 'public'),

    'disk' => [
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/media'),
        ],
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public/media'),
            'url' => env('APP_URL') . '/storage/media',
            'visibility' => 'public',
        ],
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
        ],
    ],

    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    'remote' => [
        'extra_headers' => [
            'CacheControl' => 'max-age=604800',
        ],
    ],

    'responsive_images' => [
        'width_calculator' => Spatie\MediaLibrary\ResponsiveImages\WidthCalculator\FileSizeOptimizedWidthCalculator::class,

        'use_tiny_placeholders' => true,

        'tiny_placeholder_generator' => Spatie\MediaLibrary\ResponsiveImages\TinyPlaceholderGenerator\Blurred::class,
    ],

    'enable_videos' => env('MEDIA_ENABLE_VIDEOS', false),

    'conversions' => [
        'default' => [
            'thumb' => [
                'width' => 150,
                'height' => 150,
                'fit' => 'crop',
            ],
            'small' => [
                'width' => 320,
                'height' => 320,
                'fit' => 'crop',
            ],
            'medium' => [
                'width' => 640,
                'height' => 640,
                'fit' => 'crop',
            ],
            'large' => [
                'width' => 1024,
                'height' => 1024,
                'fit' => 'crop',
            ],
        ],
    ],

    'image_optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '-m85',
            '--force',
            '--strip-all',
            '--all-progressive',
        ],
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force',
            '--quality=65-80',
        ],
        Spatie\ImageOptimizer\Optimizers\Optipng::class => [
            '-i0',
            '-o2',
            '-quiet',
        ],
        Spatie\ImageOptimizer\Optimizers\Svgo::class => [
            '--config=svgo.config.js',
        ],
        Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
            '-b',
            '-O3',
        ],
        Spatie\ImageOptimizer\Optimizers\Cwebp::class => [
            '-m 6',
            '-pass 10',
            '-mt',
            '-q 80',
        ],
    ],

    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Pdf::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Svg::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Video::class,
    ],

    'temporary_directory_path' => env('MEDIA_TEMPORARY_DIRECTORY', null),

    'queued_conversions' => env('MEDIA_QUEUED_CONVERSIONS', true),

    'force_disks' => [],

    'media_click_generator' => \Spatie\MediaLibrary\MediaCollections\HtmlableMediaClickHandler::class,

    'image_driver' => env('IMAGE_DRIVER', 'gd'),
];
