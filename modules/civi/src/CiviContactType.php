<?php
use CiviUtils as CV;

class CiviContactType
{
  public static $fieldsToAdd = [
    'image' => 'Image',
  ];

  /**
   * @return array
   */
  public static function config()
  {
    static $entityFields = [];
    if (empty($entityFields)) {
      CV::init();

      $result = civicrm_api3('Contact', 'getfields', []);
      $entityFields = CV::getEntityFields($result, [], self::$fieldsToAdd);
      if (!empty($entityFields)) {
        $entityFields['metadata'] = [
          // Label used in the customizer
          'label' => 'CiviContact',

          // Denotes that this is an object type and makes the type usable as dynamic content source
          'type' => true,
        ];
      }
    }
    return $entityFields;
  } 

}
