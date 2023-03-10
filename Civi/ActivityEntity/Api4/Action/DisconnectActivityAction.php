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

namespace Civi\ActivityEntity\Api4\Action;

use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\DAODeleteAction;
use Civi\Api4\Generic\Result;
use Webmozart\Assert\Assert;

/**
 * @method $this setActivityId(int $activityId)
 */
final class DisconnectActivityAction extends AbstractAction {

  /**
   * @var int
   * @required
   */
  protected ?int $activityId = NULL;

  private DAODeleteAction $deleteAction;

  public function __construct(DAODeleteAction $deleteAction) {
    parent::__construct($deleteAction->getEntityName(), 'disconnectActivity');
    $this->deleteAction = $deleteAction;
  }

  /**
   * @inheritDoc
   */
  public function setCheckPermissions(bool $checkPermissions) {
    parent::setCheckPermissions($checkPermissions);
    $this->deleteAction->setCheckPermissions($checkPermissions);

    return $this;
  }

  /**
   * @inheritDoc
   *
   * @throws \CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   */
  public function _run(Result $result): void {
    Assert::notNull($this->activityId);

    $deleteResult = $this->deleteAction
      ->addWhere('activity_id', '=', $this->activityId)
      ->execute();

    $result->exchangeArray($deleteResult->getArrayCopy());
  }

}
