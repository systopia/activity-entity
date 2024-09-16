<?php
declare(strict_types = 1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'activity_entity.civix.php';
// phpcs:enable

function _activity_entity_composer_autoload(): void {
  if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $classLoader = require_once __DIR__ . '/vendor/autoload.php';
    if ($classLoader instanceof \Composer\Autoload\ClassLoader) {
      // Re-register class loader to append it. (It's automatically prepended.)
      $classLoader->unregister();
      $classLoader->register();
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function activity_entity_civicrm_config(\CRM_Core_Config $config): void {
  _activity_entity_composer_autoload();
  _activity_entity_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function activity_entity_civicrm_install(): void {
  _activity_entity_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function activity_entity_civicrm_enable(): void {
  _activity_entity_civix_civicrm_enable();
}
