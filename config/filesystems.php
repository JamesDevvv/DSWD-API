<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
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
            'throw' => false,
        ],

        'trainings' => [
			'driver' => 'local',
			'root'   => storage_path('app/trainings'),
			'url' => env('APP_URL') . 'trainings',
			'visibility' => 'public',
		],


        'reports' => [
			'driver' => 'local',
			'root'   => storage_path('app/reports'),
			'url' => env('APP_URL') . 'reports',
			'visibility' => 'public',
		],

        'avatar' => [
			'driver' => 'local',
			'root'   => storage_path('app/avatar'),
			'url' => env('APP_URL') . 'avatar',
			'visibility' => 'public',
		],

        'report_archives' => [
			'driver' => 'local',
			'root'   => storage_path('app/report_archives'),
			'url' => env('APP_URL') . 'report_archives',
			'visibility' => 'public',
		],

        'augmentation' => [
			'driver' => 'local',
			'root'   => storage_path('app/augmentation'),
			'url' => env('APP_URL') . 'augmentation',
			'visibility' => 'public',
		],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        // public_path('storage') => storage_path('app/public'),
        public_path('trainings') => storage_path('app/trainings'),
        public_path('reports') => storage_path('app/reports'),
        public_path('avatar') => storage_path('app/avatar'),
        public_path('report_archives') => storage_path('app/report_archives'),
        public_path('augmentation') => storage_path('app/augmentation'),
    ],

];
