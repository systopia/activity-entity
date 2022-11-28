<?php
/*
 * Copyright (C) 2022 SYSTOPIA GmbH
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as published by
 *  the Free Software Foundation in version 3.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types = 1);

namespace Civi\Api4;

use Civi\Fixtures\ActivityFixture;
use Civi\Fixtures\GroupFixture;
use Civi\Test;
use Civi\Test\CiviEnvBuilder;
use Civi\Test\HeadlessInterface;
use Civi\Test\TransactionalInterface;
use PHPUnit\Framework\TestCase;

/**
 * @group headless
 *
 * @covers \Civi\Api4\EntityActivity
 * @covers \Civi\ActivityEntity\Api4\Action\ConnectAction
 * @covers \Civi\ActivityEntity\Api4\Action\DisconnectAction
 * @covers \Civi\ActivityEntity\Api4\Action\DisconnectActivityAction
 * @covers \Civi\ActivityEntity\Api4\Action\DisconnectEntityAction
 */
final class EntityActivityTest extends TestCase implements HeadlessInterface, TransactionalInterface {

  public function setUpHeadless(): CiviEnvBuilder {
    return Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  protected function setUp(): void {
    parent::setUp();
    OptionValue::create()->setValues([
      'option_group_id:name' => 'activity_used_for',
      'name' => 'Group',
      'value' => 'civicrm_group',
      'label' => 'Group',
    ])->execute()->first();
  }

  public function testConnectAndDisconnect(): void {
    $activity = ActivityFixture::addFixture();
    $group = GroupFixture::addFixture();

    $connectResult = EntityActivity::connect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->execute();
    static::assertCount(1, $connectResult);

    $firstConnectResult = $connectResult->first();
    unset($firstConnectResult['custom']);
    unset($firstConnectResult['check_permissions']);
    static::assertEquals([
      'id' => $firstConnectResult['id'],
      'activity_id' => $activity['id'],
      'entity_table' => 'civicrm_group',
      'entity_id' => $group['id'],
    ], $firstConnectResult);

    $getResult = EntityActivity::get()->execute();
    static::assertCount(1, $getResult);
    $firstGetResult = $getResult->first();
    static::assertEquals([
      'id' => $firstConnectResult['id'],
      'activity_id' => $activity['id'],
      'entity_table' => 'civicrm_group',
      'entity_id' => $group['id'],
      'record_type_id' => NULL,
    ], $firstGetResult);

    static::assertCount(0, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->setRecordTypeId(123)
      ->execute()
    );

    static::assertCount(1, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->execute()
    );
  }

  public function testConnectAndDisconnectWithRecordTypeId(): void {
    $activity = ActivityFixture::addFixture();
    $group = GroupFixture::addFixture();
    $this->addConnectionType('test', 123, 'Test');

    $addResult = EntityActivity::connect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->setRecordTypeId(123)
      ->execute();
    static::assertCount(1, $addResult);

    $firstAddResult = $addResult->first();
    unset($firstAddResult['custom']);
    unset($firstAddResult['check_permissions']);
    static::assertEquals([
      'id' => $firstAddResult['id'],
      'activity_id' => $activity['id'],
      'entity_table' => 'civicrm_group',
      'entity_id' => $group['id'],
      'record_type_id' => 123,
    ], $firstAddResult);

    static::assertCount(0, EntityActivity::disconnect()
      ->setActivityId($activity['id'] + 1)
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->execute()
    );

    static::assertCount(0, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'] + 1)
      ->execute()
    );

    static::assertCount(0, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Note')
      ->setEntityId($group['id'])
      ->execute()
    );

    static::assertCount(0, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->setRecordTypeId(1234)
      ->execute()
    );

    static::assertCount(1, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->setRecordTypeId(123)
      ->execute()
    );
  }

  public function testConnectAndDisconnectWithRecordTypeName(): void {
    $activity = ActivityFixture::addFixture();
    $group = GroupFixture::addFixture();
    $this->addConnectionType('test', 123, 'Test');

    $addResult = EntityActivity::connect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->setRecordTypeName('test')
      ->execute();
    static::assertCount(1, $addResult);

    $firstAddResult = $addResult->first();
    unset($firstAddResult['custom']);
    unset($firstAddResult['check_permissions']);
    static::assertEquals([
      'id' => $firstAddResult['id'],
      'activity_id' => $activity['id'],
      'entity_table' => 'civicrm_group',
      'entity_id' => $group['id'],
      'record_type_id' => 123,
    ], $firstAddResult);

    static::assertCount(0, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->setRecordTypeName('invalid')
      ->execute()
    );

    static::assertCount(1, EntityActivity::disconnect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->setRecordTypeName('test')
      ->execute()
    );
  }

  public function testDisconnectActivity(): void {
    $activity = ActivityFixture::addFixture();
    $group = GroupFixture::addFixture();

    EntityActivity::connect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->execute();

    static::assertCount(0, EntityActivity::disconnectActivity()
      ->setActivityId($activity['id'] + 1)
      ->execute()
    );

    static::assertCount(1, EntityActivity::disconnectActivity()
      ->setActivityId($activity['id'])
      ->execute()
    );
  }

  public function testDisconnectEntity(): void {
    $activity = ActivityFixture::addFixture();
    $group = GroupFixture::addFixture();

    EntityActivity::connect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->execute();

    static::assertCount(0, EntityActivity::disconnectEntity()
      ->setEntity('Note')
      ->setEntityId($group['id'])
      ->execute()
    );

    static::assertCount(0, EntityActivity::disconnectEntity()
      ->setEntity('Group')
      ->setEntityId($group['id'] + 1)
      ->execute()
    );

    static::assertCount(1, EntityActivity::disconnectEntity()
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->execute()
    );
  }

  private function addConnectionType(string $name, int $value, string $label): void {
    OptionValue::create()->setValues([
      'option_group_id:name' => 'activity_entity_connection_type',
      'name' => $name,
      'value' => $value,
      'label' => $label,
    ])->execute()->first();
  }

}
