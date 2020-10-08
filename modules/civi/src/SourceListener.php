<?php

include_once __DIR__ . '/CiviUtils.php';
include_once __DIR__ . '/CiviEventsQueryType.php';
include_once __DIR__ . '/CiviEventType.php';
include_once __DIR__ . '/CiviContactsQueryType.php';
include_once __DIR__ . '/CiviContactType.php';

class SourceListener
{
  public static function initSource($source)
  {
    $source->queryType(CiviEventsQueryType::config());
    $source->queryType(CiviContactsQueryType::config());

    //$source->objectType('CiviEventType', CiviEventType::config());
    $args	= ['CiviEvent', CiviEventType::config()];
    $source->objectType(...$args);

    $args	= ['CiviContact', CiviContactType::config()];
    $source->objectType(...$args);
  }
}
