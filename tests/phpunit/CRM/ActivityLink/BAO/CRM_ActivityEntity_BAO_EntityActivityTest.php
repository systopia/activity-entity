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

use Civi\Api4\EntityActivity;
use Civi\Api4\Group;
use Civi\Api4\OptionValue;
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
 * @covers \CRM_ActivityEntity_BAO_EntityActivity
 */
// phpcs:disable Generic.Files.LineLength.TooLong
final class CRM_ActivityEntity_BAO_EntityActivityTest extends TestCase implements HeadlessInterface, TransactionalInterface {
// phpcs:enable
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

  public function testCascadeEntityDeletion(): void {
    $activity = ActivityFixture::addFixture();
    $group = GroupFixture::addFixture();

    $addResult = EntityActivity::connect()
      ->setActivityId($activity['id'])
      ->setEntity('Group')
      ->setEntityId($group['id'])
      ->execute();

    static::assertCount(1, EntityActivity::get()->execute());

    Group::delete()
      ->addWhere('id', '=', $group['id'])
      ->execute();

    static::assertCount(0, EntityActivity::get()->execute());
  }

}
