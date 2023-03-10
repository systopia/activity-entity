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
 * @method $this setEntity(string $entity)
 * @method $this setEntityId(int $entityId)
 * @method $this setRecordTypeId(int|null $recordTypeId)
 * @method $this setRecordTypeName(string|null $recordTypeName)
 */
final class DisconnectAction extends AbstractAction {

  /**
   * @var int
   * @required
   */
  protected ?int $activityId = NULL;

  /**
   * @var string
   * @required
   */
  protected ?string $entity = NULL;

  /**
   * @var int
   * @required
   */
  protected ?int $entityId = NULL;

  /**
   * @var int|null
   */
  protected ?int $recordTypeId = NULL;

  /**
   * @var string|null
   */
  protected ?string $recordTypeName = NULL;

  private DAODeleteAction $deleteAction;

  public function __construct(DAODeleteAction $deleteAction) {
    parent::__construct($deleteAction->getEntityName(), 'disconnect');
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
    Assert::notNull($this->entity);
    Assert::notNull($this->entityId);

    $this->deleteAction->setWhere([
      ['activity_id', '=', $this->activityId],
      ['entity_table', '=', \CRM_Core_DAO_AllCoreTables::getTableForEntityName($this->entity)],
      ['entity_id', '=', $this->entityId],
    ]);
    if (NULL !== $this->recordTypeId) {
      $this->deleteAction->addWhere('record_type_id', '=', $this->recordTypeId);
    }
    if (NULL !== $this->recordTypeName) {
      $this->deleteAction->addWhere('record_type_id:name', '=', $this->recordTypeName);
    }

    $deleteResult = $this->deleteAction->execute();
    $result->exchangeArray($deleteResult->getArrayCopy());
  }

}
