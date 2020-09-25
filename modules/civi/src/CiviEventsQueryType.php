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
      $result = civicrm_api3('Event', 'get', [
        'sequential' => 1,
        'options' => ['limit' => $args['limit']],
      ]);
      $entities = $result['values'];
    }
    return $entities;
  }
}
