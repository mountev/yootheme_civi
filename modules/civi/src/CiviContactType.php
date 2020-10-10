<?php
use CiviUtils as CV;

class CiviContactType
{
  public static $fieldsToKeep = [
    'id',
    'contact_type',
    'contact_sub_type',
    'legal_identifier',
    'external_identifier',
    'sort_name',
    'display_name',
    'nick_name',
    'legal_name',
    'image_URL',
    'first_name',
    'middle_name',
    'last_name',
    'prefix_id',
    'suffix_id',
    'formal_title',
    'postal_greeting_display',
    'addressee_display',
    'job_title',
    'gender_id',
    'birth_date',
    'is_deceased',
    'household_name',
    'organization_name',
    'sic_code',
    'current_employer_id',
    'street_address',
    'supplemental_address_1',
    'supplemental_address_2',
    'supplemental_address_3',
    'current_employer',
    'city',
    'postal_code',
    'geo_code_1',
    'geo_code_2',
    'state_province_name',
    'state_province',
    'country',
    'worldregion',
    'phone',
    'email',
    'on_hold',
    'im',
    'group',
    'tag',
    'uf_user',
  ];

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
      $entityFields = CV::getEntityFields($result, self::$fieldsToKeep, self::$fieldsToAdd);
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
