<?php

use CiviUtils as CV;

class CiviEventsQueryType
{
  public static function config()
  {
    return [
      'fields' => [
        'civievents' => [
          'type' => [
            'listOf' => 'CiviEvent',
          ],

          // Arguments passed to the resolver function
          'args' => [
            'offset' => [
              'type' => 'Int',
            ],
            'limit' => [
              'type' => 'Int',
            ],
            'type_id' => [
              'type' => 'String'
            ],
            'order' => [
              'type' => 'String',
            ],
            'order_direction' => [
              'type' => 'String',
            ],
          ],

          'metadata' => [
            // Label in dynamic content select box
            'label' => 'CiviCRM Events',

            // Option group in dynamic content select box
            'group' => 'CiviCRM',

            // Fields to input arguments in the customizer
            'fields' => [
              // The array key corresponds to a key in the 'args' array above
              'type_id' => [
                // Field label
                'label' => 'Type ID',
                // Field description
                'description' => 'Input a type ID.',
                // Default or custom field types can be used
                'type' => 'text',
              ],

              '_offset' => [
                'description' => 'Set the starting point and limit the number of events.',
                'type' => 'grid',
                'width' => '1-2',

                'fields' => [
                  'offset' => [
                    'label' => 'Start',
                    'type' => 'number',
                    'default' => 0,
                    'modifier' => 1,
                    'attrs' => [
                      'min' => 1,
                      'required' => true,
                    ],
                  ],
                  'limit' => [
                    'label' => 'Quantity',
                    'type' => 'limit',
                    'default' => 10,
                    'attrs' => [
                      'min' => 1,
                    ],
                  ],
                ],
              ],
              '_order' => [
                'type' => 'grid',
                'width' => '1-2',
                'fields' => [
                  'order' => [
                    'label' => 'Order',
                    'type' => 'select',
                    'default' => 'title',
                    'options' => [
                      'Event Title' => 'title',
                      'Event Start Date' => 'start_date',
                      'Event End Date' => 'end_date',
                    ],
                  ],
                  'order_direction' => [
                    'label' => 'Direction',
                    'type' => 'select',
                    'default' => 'ASC',
                    'options' => [
                      'Ascending' => 'ASC',
                      'Descending' => 'DESC',
                    ],
                  ],
                ],
              ],
            ],
          ],

          'extensions' => [
            'call' => __CLASS__ . '::resolve',
          ],
        ],
      ],
    ];
  }

  public static function resolve($root, array $args)
  {
    static $entities = [];
    if (empty($entities)) {
      CV::init();

      $options = CV::getOptions($args);
      $options['is_active'] = 1;

      $result = civicrm_api3('Event', 'get', [
        'sequential' => 1,
        'options' => $options,
        // we could fetch location with chaining but that would result in many fields
        // for now we would stick to display field as used by event info page
        //'api.LocBlock.get' => ['api.Address.get' => []],
      ]);
      // for now we would stick to display field as used by event info page
      foreach ($result['values'] as &$event) {
        $params = ['entity_id' => $event['id'], 'entity_table' => 'civicrm_event'];
        $location = CRM_Core_BAO_Location::getValues($params, TRUE);
        if (!empty($location['address'][1])) {
          foreach (CV::getEventLocAddressFields() as $field) {
            $event["location_address_{$field}"] = CRM_Utils_Array::value($field, $location['address'][1]);
          }
        }
        $url = CRM_Utils_System::url('civicrm/event/register',
          "id={$event['id']}&reset=1",
          TRUE, NULL, TRUE,
          TRUE
        );
        $event["registration_url"] = $url;
      }
      $entities = $result['values'];
    }
    return $entities;
  }
}
