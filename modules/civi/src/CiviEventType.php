<?php
use CiviUtils as CV;

class CiviEventType
{
  public static $fieldsToKeep = [
    'id',
    'event_title',
    'summary',
    'event_description',
    'event_type_id',
    'event_start_date',
    'event_end_date',
    'is_online_registration',
    'registration_link_text',
    'registration_start_date',
    'registration_end_date',
    'max_participants',
    'is_monetary',
    'financial_type_id',
    'is_map',
    'default_role_id',
    'intro_text',
  ];
  public static $fieldsToAdd = [
    'fee_block' => 'Fee Block',
    'registration_url'      => 'Registration URL',
    'registration_url_link' => 'Registration URL href Link',
  ];

  /**
   * @return array
   */
  public static function config()
  {
    static $entityFields = [];
    if (empty($entityFields)) {
      CV::init();

      $result = civicrm_api3('Event', 'getfields', []);
      $entityFields = CV::getEntityFields($result, self::$fieldsToKeep, self::$fieldsToAdd);

      // address fields
      foreach (CV::getEventLocAddressFields(TRUE) as $field => $title) {
        $key = "location_address_{$field}";
        $entityFields['fields'][$key] = [
          'name' => $key,
          'type' => 'String',
          'metadata' => [
            'label' => $title,
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
    return $entityFields;
  } 

  /**
   * @return array
   */
  public static function getFieldList()
  {
    $fieldList = [];
    $entityFields = self::config();
    foreach ($entityFields['fields'] as $key => $info) {
      $fieldList[$info['metadata']['label'] . " (cvevent_{$info['name']})"] = "cvevent_{$info['name']}";
    }
    return $fieldList;
  }
}
