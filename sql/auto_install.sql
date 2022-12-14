-- +--------------------------------------------------------------------+
-- | Copyright CiviCRM LLC. All rights reserved.                        |
-- |                                                                    |
-- | This work is published under the GNU AGPLv3 license with some      |
-- | permitted exceptions and without any warranty. For full license    |
-- | and copyright information, see https://civicrm.org/licensing       |
-- +--------------------------------------------------------------------+
--
-- Generated from schema.tpl
-- DO NOT EDIT.  Generated by CRM_Core_CodeGen
--
-- /*******************************************************
-- *
-- * Clean up the existing tables - this section generated from drop.tpl
-- *
-- *******************************************************/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `civicrm_entity_activity`;

SET FOREIGN_KEY_CHECKS=1;
-- /*******************************************************
-- *
-- * Create new tables
-- *
-- *******************************************************/

-- /*******************************************************
-- *
-- * civicrm_entity_activity
-- *
-- * Connect activities to other entities
-- *
-- *******************************************************/
CREATE TABLE `civicrm_entity_activity` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique ActivityEntity ID',
  `activity_id` int unsigned COMMENT 'FK to Activity',
  `entity_table` varchar(64) COMMENT 'Physical table name for entity being joined',
  `entity_id` int unsigned NOT NULL COMMENT 'FK to entity table specified in entity_table column.',
  `record_type_id` int unsigned NULL COMMENT 'Determines the type of connection',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UI_activity_entity`(activity_id, entity_table, entity_id, record_type_id),
  INDEX `index_record_type`(activity_id, record_type_id),
  CONSTRAINT FK_civicrm_entity_activity_activity_id FOREIGN KEY (`activity_id`) REFERENCES `civicrm_activity`(`id`) ON DELETE CASCADE
)
ENGINE=InnoDB;
