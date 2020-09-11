<?php

use YOOtheme\Builder;
use YOOtheme\Path;

include_once __DIR__ . '/src/AssetsListener.php';
include_once __DIR__ . '/src/SettingsListener.php';
include_once __DIR__ . '/src/SourceListener.php';
include_once __DIR__ . '/src/SourceController.php';

return [
  'routes' => [
    ['get', '/joomla/civievent', [SourceController::class, 'events']],
  ],

  'events' => [

    'source.init' => [
      SourceListener::class => 'initSource',
    ],


    // Add asset files
    'theme.head' => [
      AssetsListener::class => 'initHead',
    ],

    // Add settings Panels
    'customizer.init' => [
      SettingsListener::class => 'initCustomizer',
    ]

  ],

  // Add builder elements
  'extend' => [

    Builder::class => function (Builder $builder) {
      $builder->addTypePath(Path::get('./elements/*/element.json'));
    }

]

];
