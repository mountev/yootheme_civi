<?php
use CiviUtils as CV;

class CiviEventType
{
  /**
   * @return array
   */
  public static function config()
  {
    static $entityFields = [];
    if (empty($entityFields)) {
      CV::init();

      $result = civicrm_api3('Event', 'getfields', [
        'api_action' => "",
      ]);
      $entityFields = [];
      if (!empty($result['values'])) {
        $count = 0;
        foreach ($result['values'] as $key => $field) {
          $entityFields['fields'][$key] = [
            'name' => $key,
            'type' => 'String',
            'metadata' => [
              'label' => $field['title'],
              'group' => 'CiviCRM'
            ],
          ];
        }
        if (!empty($entityFields)) {
          $entityFields['metadata'] = [
            // Label used in the customizer
            'label' => 'CiviEvent',

            // Denotes that this is an object type and makes the type usable as dynamic content source
            'type' => true,
          ];
        }
      }
    }
    return $entityFields;
  } 

}
