<?php

include_once __DIR__ . '/CiviUtils.php';
include_once __DIR__ . '/CiviEventsQueryType.php';
include_once __DIR__ . '/CiviEventType.php';

class SourceListener
{
  public static function initSource($source)
  {
    $source->queryType(CiviEventsQueryType::config());
    //$source->objectType('CiviEventType', CiviEventType::config());
    $args	= ['CiviEvent', CiviEventType::config()];
    $source->objectType(...$args);
  }
}
