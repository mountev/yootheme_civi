<?php

use CiviUtils as CV;

class CiviEventsQueryType
{
  public static function config()
  {
    CV::init();
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
            'event_type_id' => [
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
            'label' => 'CiviCRM Events',

            // Option group in dynamic content select box
            'group' => 'CiviCRM',

            // Fields to input arguments in the customizer
            'fields' => [
              // The array key corresponds to a key in the 'args' array above
              'event_type_id' => [
                // Field label
                'label' => 'Event Type',
                // Field description
                'description' => 'Select an event type.',
                // Default or custom field types can be used
                'type' => 'select',
                'default' => 0,
                'options' => ['- ANY -' => 0] + array_flip(CRM_Event_PseudoConstant::eventType()),
              ],

              'url_filter_field' => [
                // Field label
                'label' => 'URL Filter Field',
                // Field description
                'description' => 'Select a field to filter with when supplied from URL.',
                // Default or custom field types can be used
                'type' => 'select',
                'default' => '',
                'options' => ['- NONE -' => ''] + CiviEventType::getFieldList(),
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
    $key = implode('_', $args);
    if (empty($entities[$key])) {
      CV::init();

      $options = CV::getOptions($args);

      $params  = [
        'sequential' => 1,
        'is_active'  => 1,
        'options'    => $options,
        // we could fetch location with chaining but that would result in many fields
        // for now we would stick to display field as used by event info page
        //'api.LocBlock.get' => ['api.Address.get' => []],
      ];
      foreach (['event_type_id'] as $para) {
        if (!empty($args[$para])) {
          $params[$para] = $args[$para];
        }
      }
      CV::applyUrlFilter($args, $params, 'cvevent_');

      $result = civicrm_api3('Event', 'get', $params);
      // for now we would stick to display field as used by event info page
      foreach ($result['values'] as &$event) {
        $params = ['entity_id' => $event['id'], 'entity_table' => 'civicrm_event'];
        $location = CRM_Core_BAO_Location::getValues($params, TRUE);
        if (!empty($location['address'][1])) {
          foreach (CV::getEventLocAddressFields() as $field) {
            $event["location_address_{$field}"] = CRM_Utils_Array::value($field, $location['address'][1]);
          }
        }
        $event["registration_url"] = CRM_Utils_System::url('civicrm/event/register',
          "id={$event['id']}&reset=1", TRUE, NULL, TRUE, TRUE);

        $feeBlock = [];
        $priceSetId = CRM_Price_BAO_PriceSet::getFor('civicrm_event', $event['id']);
        if ($priceSetId) {
          $setDetails = CRM_Price_BAO_PriceSet::getSetDetail($priceSetId, TRUE, TRUE);
          foreach ($setDetails[$priceSetId]['fields'] as $fid => $fldVal) {
            if (count($fldVal['options']) > 1) {
              $feeBlock[] = $fldVal['label'];
              foreach ($fldVal['options'] as $opt) {
                $feeBlock[] = ' - ' . $opt['label'] . ' ' . CRM_Utils_Money::format($opt['amount']); 
              }
            } else {
              foreach ($fldVal['options'] as $opt) {
                $feeBlock[] = $fldVal['label'] . ' ' . CRM_Utils_Money::format($opt['amount']); 
              }
            }
          }
        }
        $event["fee_block"] = implode('<br/>', $feeBlock);

        if (!empty($event['registration_url'])) {
          $event['registration_url_link'] = "<a href='{$event['registration_url']}'>Register</a>";
        }
        if (!empty($contact['geo_code_1'])) {
          $contact['geo_code'] = "{$contact['geo_code_1']}, {$contact['geo_code_2']}";
        }
      }
      $entities[$key] = $result['values'];
    }
    return $entities[$key];
  }
}
