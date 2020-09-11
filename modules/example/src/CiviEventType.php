<?php

class CiviEventType
{
    /**
     * @return array
     */
  public static function config()
  {
    return [
      'fields' => [
        'title' => [
          'name' => 'title',
          'type' => 'String',
          'metadata' => [
            // Label used in the customizer
            'label' => 'Event Title',
            // Option group within the dynamic content select box
            'group' => 'My Group'
          ],

          //'extensions' => [
          //  // A static resolver function to resolve the field value
          //  'call' => __CLASS__ . '::resolve',
          //]
        ]
      ],

      'metadata' => [
        // Label used in the customizer
        'label' => 'CiviEvent',

        // Denotes that this is an object type and makes the type usable as dynamic content source
        'type' => true,

      ],
    ];
  } 

  //public static function resolve($obj, $args, $context, $info)
  //{
  //  // Query some data …

  //  return 'the my data';
  //}
}
