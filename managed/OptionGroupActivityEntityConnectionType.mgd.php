<?php
declare(strict_types = 1);

return [
  [
    'name' => 'OptionGroup_activity_entity_connection_type',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'activity_entity_connection_type',
        'title' => 'Activity Connection Type',
        'description' => 'Types of connections between activities and other entities',
        'data_type' => 'String',
        'is_reserved' => TRUE,
        'is_active' => TRUE,
        'is_locked' => FALSE,
        'option_value_fields' => [
          'name',
          'label',
          'description',
        ],
      ],
    ],
  ],
];
