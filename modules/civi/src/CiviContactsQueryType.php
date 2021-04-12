<?php

use CiviUtils as CV;

class CiviContactsQueryType
{
  public static function config()
  {
    CV::init();
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
            'contact_type' => [
              'type' => 'String'
            ],
            'group' => [
              'type' => 'Int'
            ],
            'url_filter_field' => [
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
              'contact_type' => [
                // Field label
                'label' => 'Contact Type',
                // Field description
                'description' => 'Select a contact type.',
                // Default or custom field types can be used
                'type' => 'select',
                'default' => '',
                'options' => [
                  '- ANY -'      => '',
                  'Individual'   => 'Individual',
                  'Organization' => 'Organization',
                  'Household'    => 'Household',
                ],
              ],
              'group' => [
                // Field label
                'label' => 'Contact Group',
                // Field description
                'description' => 'Select a contact group.',
                // Default or custom field types can be used
                'type' => 'select',
                'default' => 0,
                'options' => ['- ANY -' => 0] + array_flip(CRM_Contact_BAO_Group::getGroupsHierarchy(CRM_Core_PseudoConstant::group(), NULL, '|-- ', TRUE)),
              ],

              'url_filter_field' => [
                // Field label
                'label' => 'URL Filter Field',
                // Field description
                'description' => 'Select a field to filter with when supplied from URL.',
                // Default or custom field types can be used
                'type' => 'select',
                'default' => '',
                'options' => ['- NONE -' => ''] + CiviContactType::getFieldList(),
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
    $key = implode('_', $args);
    if (empty($entities[$key])) {
      CV::init();
      $entities[$key] = [];

      $params = CV::getOptions($args);
      // apply other YTP UI filters
      foreach (['contact_type'] as $para) {
        if (!empty($args[$para])) {
          $params['where'][] = [$para, '=', $args[$para]];
        }
      }
      // filter with joins
      if (!empty($args['group'])) {
        $params['join'][] = ['GroupContact AS group_contact', FALSE];
        $params['where'][] = ['group_contact.group_id', '=', $args['group']];
      }
      CV::applyUrlFilter($args, $params);

      $result = civicrm_api4('Contact', 'get', $params);
      foreach ($result as &$contact) {
        if (!empty($contact['image_URL'])) {
          $contact['image'] = "<img src='{$contact['image_URL']}'>";
        }
        if (!empty($contact['geo_code_1'])) {
          $contact['geo_code'] = "{$contact['geo_code_1']}, {$contact['geo_code_2']}";
        }
        $entities[$key][] = $contact;
      }
    }
    return $entities[$key];
  }
}
