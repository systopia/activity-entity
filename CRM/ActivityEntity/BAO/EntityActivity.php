<?php
declare(strict_types = 1);

use Civi\Core\DAO\Event\PreDelete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

// phpcs:disable Generic.Files.LineLength.TooLong
class CRM_ActivityEntity_BAO_EntityActivity extends CRM_ActivityEntity_DAO_EntityActivity implements EventSubscriberInterface {
// phpcs:enable
  use CRM_Core_DynamicFKAccessTrait;

  /**
   * @inheritDoc
   *
   * @codeCoverageIgnore
   */
  public static function getSubscribedEvents(): array {
    return [
      'civi.dao.preDelete' => 'onPreDelete',
    ];
  }

  public static function onPreDelete(PreDelete $event): void {
    self::cascadeEntityDeletion($event->object->tableName(), $event->object->id ?? NULL);
  }

  /**
   * @param int|string|null $entityId
   */
  private static function cascadeEntityDeletion(string $entityTable, $entityId): void {
    static $deleteQuery;

    if (self::isUsedForTable($entityTable) && is_numeric($entityId)) {
      $deleteQuery ??= sprintf('DELETE FROM `%s` WHERE entity_table = %%1 AND entity_id = %%2', static::getTableName());
      CRM_Core_DAO::executeQuery(
        $deleteQuery, [
          1 => [$entityTable, 'String'],
          2 => [$entityId, 'Integer'],
        ]
      );
    }
  }

  private static function isUsedForTable(string $tableName): bool {
    static $usedFor;
    $usedFor ??= CRM_Core_OptionGroup::values('activity_used_for');

    return isset($usedFor[$tableName]);
  }

}
