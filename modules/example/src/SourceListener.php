<?php

//use YOOtheme\Config;
//use YOOtheme\Path;
//use YOOtheme\View;
include_once __DIR__ . '/CiviEventQueryType.php';
include_once __DIR__ . '/CiviEventType.php';

class SourceListener
{
  public static function initSource($source)
  {
    $source->queryType(CiviEventQueryType::config());
    $source->objectType('CiviEventType', CiviEventType::config());
  }
}
