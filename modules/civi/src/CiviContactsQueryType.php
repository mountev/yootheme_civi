<?php

use CiviUtils as CV;

class CiviContactsQueryType
{
  public static function config()
  {
    return [
      'fields' => [
        'civicontacts' => [
          'type' => [
            'listOf' => 'CiviContact',
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
            'label' => 'CiviCRM Contacts',

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
                'description' => 'Set the starting point and limit the number of contacts.',
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
                    'default' => 'sort_name',
                    'options' => [
                      'Sort Name' => 'sort_name',
                      'Display Name' => 'display_name',
                      'Last Name'  => 'last_name',
                      'First Name' => 'first_name',
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
      $options = [
        'limit' => $args['limit']
      ];
      if (!empty($args['order'])) {
        $options['sort'] = $args['order'] . ' ' . $args['order_direction'];
      }
      $result = civicrm_api3('Contact', 'get', [
        'sequential' => 1,
        'options'    => $options,
      ]);
      $entities = $result['values'];
    }
    return $entities;
  }
}
