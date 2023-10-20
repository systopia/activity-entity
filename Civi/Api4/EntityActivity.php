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

use Civi\ActivityEntity\Api4\Action\ConnectAction;
use Civi\ActivityEntity\Api4\Action\DisconnectAction;
use Civi\ActivityEntity\Api4\Action\DisconnectActivityAction;
use Civi\ActivityEntity\Api4\Action\DisconnectEntityAction;

/**
 * Connect activities with other entities.
 *
 * The record_type_id field determines the type of connection.
 * @ui_join_filters record_type_id
 *
 * @searchable bridge
 * @see \Civi\Api4\Activity
 * @package Civi\Api4
 */
final class EntityActivity extends Generic\DAOEntity {
  use Generic\Traits\EntityBridge;

  /**
   * Connect an activity with an entity.
   */
  public static function connect(bool $checkPermissions = TRUE): ConnectAction {
    return (new ConnectAction(static::create()))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * Disconnect an entity from an activity.
   */
  public static function disconnect(bool $checkPermissions = TRUE): DisconnectAction {
    return (new DisconnectAction(static::delete()))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * Disconnect all entity connections with an activity.
   */
  public static function disconnectActivity(bool $checkPermissions = TRUE): DisconnectActivityAction {
    return (new DisconnectActivityAction(static::delete()))
      ->setCheckPermissions($checkPermissions);
  }

  /**
   * Disconnect all activity connections with an entity.
   */
  public static function disconnectEntity(bool $checkPermissions = TRUE): DisconnectEntityAction {
    return (new DisconnectEntityAction(static::delete()))
      ->setCheckPermissions($checkPermissions);
  }

}
