<?php

class CiviEventType
{
    /**
     * @return array
     */
  public static function config()
  {
    static $output = [];
    if (empty($output)) {
      require_once JPATH_ROOT . '/components/com_civicrm/civicrm.settings.php';
      require_once 'CRM/Core/ClassLoader.php';
      CRM_Core_ClassLoader::singleton()->register();
      require_once 'PEAR.php';
      $config = CRM_Core_Config::singleton();

      $result = civicrm_api3('Event', 'getfields', [
        'api_action' => "",
      ]);
      $output = [];
      if (!empty($result['values'])) {
        $count = 0;
        foreach ($result['values'] as $key => $field) {
          $output['fields'][$field['name']] = [
            'name' => $field['name'],
            'type' => 'String',
            'metadata' => [
              'label' => $field['title'],
              'group' => 'My CiviCRM'
            ],
            'extensions' => [
              'call' => [
                'func' => __CLASS__ . '::resolve',
                'args' => [
                  'field' => $key
                ]
              ]
            ]
          ];
          $count++;
          if ($count >= 13) {
            break;
          }
        }
        if (!empty($output)) {
          $output['metadata'] = [
            // Label used in the customizer
            'label' => 'CiviEvent',

            // Denotes that this is an object type and makes the type usable as dynamic content source
            'type' => true,
          ];
        }
      }
      CRM_Core_Error::debug_var('$output', $output);
    }
    return $output;

    //return [
    //  'fields' => [
    //    'title' => [
    //      'name' => 'title',
    //      'type' => 'String',
    //      'metadata' => [
    //        // Label used in the customizer
    //        'label' => 'Event Title',
    //        // Option group within the dynamic content select box
    //        'group' => 'My Group'
    //      ],

    //      'extensions' => [
    //        // A static resolver function to resolve the field value
    //        'call' => __CLASS__ . '::resolve',
    //      ]
    //    ]
    //  ],

    //  'metadata' => [
    //    // Label used in the customizer
    //    'label' => 'CiviEvent',

    //    // Denotes that this is an object type and makes the type usable as dynamic content source
    //    'type' => true,

    //  ],
    //];
  } 

  public static function resolve($obj, $args, $context, $info)
  {
    // Query some data …

    ////CRM_Core_Error::debug_var('$obj', $obj);
    ////CRM_Core_Error::debug_var('$args', $args);
    ////CRM_Core_Error::debug_var('$context', $context);
    ////CRM_Core_Error::debug_var('$info', $info);

      require_once JPATH_ROOT . '/components/com_civicrm/civicrm.settings.php';
      require_once 'CRM/Core/ClassLoader.php';
      CRM_Core_ClassLoader::singleton()->register();
      require_once 'PEAR.php';
      $config = CRM_Core_Config::singleton();

    $result = civicrm_api3('Event', 'get', [
      'sequential' => 1,
      'options' => ['limit' => 1],
    ]);
    CRM_Core_Error::debug_var('$result', $result);
    CRM_Core_Error::debug_var('$args', $args);
    if (!empty($result['values']) && !empty($args['field'])) {
      $output = $result['values'][0][$args['field']];
      CRM_Core_Error::debug_var('$output', $output);
      return $output;
    }
    return 'Unknown';
  }
}
