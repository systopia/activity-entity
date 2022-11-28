<?php
declare(strict_types = 1);

return [
  [
    'name' => 'OptionGroup_activity_used_for',
    'entity' => 'OptionGroup',
    'cleanup' => 'unused',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'activity_used_for',
        'title' => 'Activity Used For',
        'description' => 'Table names allowed to reference for activity links',
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
