<?php

class CiviUtils {

  public static function init() {
    require_once JPATH_ROOT . '/components/com_civicrm/civicrm.settings.php';
    require_once 'CRM/Core/ClassLoader.php';
    CRM_Core_ClassLoader::singleton()->register();
    require_once 'PEAR.php';
    $config = CRM_Core_Config::singleton();
  }

  public static function getEventLocAddressFields($withTitles = FALSE) {
    $fields = [
      'geo_code_1'     => 'Location Address Latitude',
      'geo_code_2'     => 'Location Address Longitude',
      'location_type'  => 'Location Address Location Type',
      'state_province' => 'Location Address State Province',
      'country'        => 'Location Address Country',
      'world_region'   => 'Location Address World Region',
      'display'        => 'Location Address HTML Display',
      'display_text'   => 'Location Address Text Display',
    ];
    if ($withTitles) {
      return $fields;
    }
    return array_keys($fields);
  }

  public static function getEntityFields($apiResult, $fieldsToKeep = [], $fieldsToAdd = []) {
    $entityFields = [];
    if (!empty($apiResult['values'])) {
      foreach ($apiResult['values'] as $key => $field) {
        if (empty($fieldsToKeep) || 
          (!empty($fieldsToKeep) && in_array($key, $fieldsToKeep))
        ) {
          $entityFields['fields'][$key] = [
            'name' => $key,
            'type' => 'String',
            'metadata' => [
              'label' => $field['title'],
              'group' => 'CiviCRM'
            ],
          ];
          if (stripos($key, '_date')) {
            $entityFields['fields'][$key]['metadata']['filters'] = ['date'];
          }
        }
      }
    }
    if (!empty($fieldsToAdd)) {
      foreach ($fieldsToAdd as $key => $title) {
        $entityFields['fields'][$key] = [
          'name' => $key,
          'type' => 'String',
          'metadata' => [
            'label' => $title,
            'group' => 'CiviCRM'
          ],
        ];
      }
    }
    return $entityFields;
  }

  public static function getOptions(array $args) {
    $options = [
      'limit' => 10
    ];
    if (!empty($args['limit'])) {
      $options['limit'] = $args['limit'];
    }
    if (!empty($args['order'])) {
      $options['sort'] = $args['order'] . ' ' . $args['order_direction'];
    }
    return $options;
  }
}
