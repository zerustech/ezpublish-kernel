<?php
/**
 * File containing the Content Type Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Type;

use eZ\Publish\SPI\Persistence\Content\Type;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;
use eZ\Publish\SPI\Persistence\Content\Type\UpdateStruct;
use eZ\Publish\SPI\Persistence\Content\Type\Group;
use eZ\Publish\SPI\Persistence\Content\Type\Group\UpdateStruct as GroupUpdateStruct;

/**
 * Base class for content type gateways.
 */
abstract class Gateway
{
    /**
     * Inserts the given $group.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type\Group $group
     *
     * @return mixed Group ID
     */
    abstract public function insertGroup( Group $group );

    /**
     * Updates a group with data in $group.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type\Group\UpdateStruct $group
     *
     * @return void
     */
    abstract public function updateGroup( GroupUpdateStruct $group );

    /**
     * Returns the number of Groups the type is assigned to.
     *
     * @param int $typeId
     * @param int $status
     *
     * @return int
     */
    abstract public function countGroupsForType( $typeId, $status );

    /**
     * Deletes the Group with the given $groupId.
     *
     * @param int $groupId
     *
     * @return void
     */
    abstract public function deleteGroup( $groupId );

    /**
     * Returns an array with data about the Group with $groupId.
     *
     * @param int $groupId
     *
     * @return array
     */
    abstract public function loadGroupData( $groupId );

    /**
     * Returns an array with data about the Group with $identifier.
     *
     * @param int $identifier
     *
     * @return array
     */
    abstract public function loadGroupDataByIdentifier( $identifier );

    /**
     * Returns an array with data about all Group objects.
     *
     * @return array
     */
    abstract public function loadAllGroupsData();

    /**
     * Loads data for all Types in $status in $groupId.
     *
     * @param mixed $groupId
     * @param int $status
     *
     * @return string[][]
     */
    abstract public function loadTypesDataForGroup( $groupId, $status );

    /**
     * Inserts a new content type.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $type
     * @param mixed|null $typeId
     *
     * @return mixed Type ID
     */
    abstract public function insertType( Type $type, $typeId = null );

    /**
     * Insert assignment of $typeId to $groupId.
     *
     * @param mixed $typeId
     * @param int $status
     * @param mixed $groupId
     *
     * @return void
     */
    abstract public function insertGroupAssignment( $typeId, $status, $groupId );

    /**
     * Deletes a group assignments for a Type.
     *
     * @param mixed $groupId
     * @param mixed $typeId
     * @param int $status
     *
     * @return void
     */
    abstract public function deleteGroupAssignment( $groupId, $typeId, $status );

    /**
     * Loads an array with data about field definition referred $id and $status.
     *
     * @param mixed $id field definition id
     * @param int $status
     *
     * @return array Data rows.
     */
    abstract public function loadFieldDefinition( $id, $status );

    /**
     * Inserts a $fieldDefinition for $typeId.
     *
     * @param mixed $typeId
     * @param int $status
     * @param \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition $fieldDefinition
     *
     * @return mixed Field definition ID
     */
    abstract public function insertFieldDefinition( $typeId, $status, FieldDefinition $fieldDefinition );

    /**
     * Deletes a field definition.
     *
     * @param mixed $typeId
     * @param int $status
     * @param mixed $fieldDefinitionId
     *
     * @return void
     */
    abstract public function deleteFieldDefinition( $typeId, $status, $fieldDefinitionId );

    /**
     * Updates a $fieldDefinition for $typeId.
     *
     * @param mixed $typeId
     * @param int $status
     * @param \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition $fieldDefinition
     *
     * @return void
     */
    abstract public function updateFieldDefinition( $typeId, $status, FieldDefinition $fieldDefinition );

    /**
     * Update a type with $updateStruct.
     *
     * @param mixed $typeId
     * @param int $status
     * @param \eZ\Publish\SPI\Persistence\Content\Type\UpdateStruct $updateStruct
     *
     * @return void
     */
    abstract public function updateType( $typeId, $status, UpdateStruct $updateStruct );

    /**
     * Update a type status
     *
     * @param mixed $typeId
     *
     * @return void
     */
    abstract public function publish( $typeId );

    /**
     * Loads an array with data about $typeId in $status.
     *
     * @param mixed $typeId
     * @param int $status
     *
     * @return array Data rows.
     */
    abstract public function loadTypeData( $typeId, $status );

    /**
     * Loads an array with data about the type referred to by $identifier in
     * $status.
     *
     * @param string $identifier
     * @param int $status
     *
     * @return array(int=>array(string=>mixed)) Data rows.
     */
    abstract public function loadTypeDataByIdentifier( $identifier, $status );

    /**
     * Loads an array with data about the type referred to by $remoteId in
     * $status.
     *
     * @param mixed $remoteId
     * @param int $status
     *
     * @return array(int=>array(string=>mixed)) Data rows.
     */
    abstract public function loadTypeDataByRemoteId( $remoteId, $status );

    /**
     * Deletes a Type completely.
     *
     * @param mixed $typeId
     * @param int $status
     *
     * @return void
     */
    abstract public function delete( $typeId, $status );
}
