<?php

class CiviUtils {

  public static function init() {
    require_once JPATH_ROOT . '/components/com_civicrm/civicrm.settings.php';
    require_once 'CRM/Core/ClassLoader.php';
    CRM_Core_ClassLoader::singleton()->register();
    require_once 'PEAR.php';
    $config = CRM_Core_Config::singleton();
  }
}
