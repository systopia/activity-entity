<?php
// This file declares a new entity type. For more details, see "hook_civicrm_entityTypes" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
return [
  [
    'name' => 'EntityActivity',
    'class' => 'CRM_ActivityEntity_DAO_EntityActivity',
    'table' => 'civicrm_entity_activity',
  ],
];
