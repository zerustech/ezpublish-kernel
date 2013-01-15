<?php
/**
 * File containing the Content Type Handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Type;

use eZ\Publish\SPI\Persistence\Content\Type;
use eZ\Publish\SPI\Persistence\Content\Type\Handler as BaseContentTypeHandler;
use eZ\Publish\SPI\Persistence\Content\Type\CreateStruct;
use eZ\Publish\SPI\Persistence\Content\Type\UpdateStruct;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;
use eZ\Publish\SPI\Persistence\Content\Type\Group;
use eZ\Publish\SPI\Persistence\Content\Type\Group\CreateStruct as GroupCreateStruct;
use eZ\Publish\SPI\Persistence\Content\Type\Group\UpdateStruct as GroupUpdateStruct;
use eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\SqlNg\Content\Type\Update\Handler as UpdateHandler;
use eZ\Publish\Core\Persistence\SqlNg\Exception;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use eZ\Publish\Core\Base\Exceptions\BadStateException;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;

/**
 */
class Handler implements BaseContentTypeHandler
{
    /**
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Gateway
     */
    protected $contentTypeGateway;

    /**
     * Mappper for Type objects.
     *
     * @var Mapper
     */
    protected $mapper;

    /**
     * Creates a new content type handler.
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Gateway $contentTypeGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Mapper $mapper
     */
    public function __construct(
        Gateway $contentTypeGateway,
        Mapper $mapper )
    {
        $this->contentTypeGateway = $contentTypeGateway;
        $this->mapper = $mapper;
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\Type\Group\CreateStruct $createStruct
     *
     * @return Group
     */
    public function createGroup( GroupCreateStruct $createStruct )
    {
        $group = $this->mapper->createGroupFromCreateStruct(
            $createStruct
        );

        $group->id = $this->contentTypeGateway->insertGroup(
            $group
        );

        return $group;
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\Type\Group\UpdateStruct $struct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type\Group
     */
    public function updateGroup( GroupUpdateStruct $struct )
    {
        $this->contentTypeGateway->updateGroup(
            $struct
        );
        return $this->loadGroup( $struct->id );
    }

    /**
     * @param mixed $groupId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\BadStateException If type group contains types
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If type group with id is not found
     */
    public function deleteGroup( $groupId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @param mixed $groupId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If type group with $groupId is not found
     *
     * @return Group
     */
    public function loadGroup( $groupId )
    {
        $groups = $this->mapper->extractGroupsFromRows(
            $this->contentTypeGateway->loadGroupData( $groupId )
        );

        if ( count( $groups ) !== 1 )
        {
            throw new NotFoundException( 'group', $groupId );
        }

        return $groups[0];
    }

    /**
     * @param string $identifier
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If type group with $identifier is not found
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type\Group
     */
    public function loadGroupByIdentifier( $identifier )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @return Group[]
     */
    public function loadAllGroups()
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @param mixed $groupId
     * @param int $status
     *
     * @return Type[]
     */
    public function loadContentTypes( $groupId, $status = 0 )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @param int $contentTypeId
     * @param int $status
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type
     */
    public function load( $contentTypeId, $status = Type::STATUS_DEFINED )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads a (defined) content type by identifier
     *
     * @param string $identifier
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If defined type is not found
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type
     */
    public function loadByIdentifier( $identifier )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads a (defined) content type by remote id
     *
     * @param mixed $remoteId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If defined type is not found
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type
     */
    public function loadByRemoteId( $remoteId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\Type\CreateStruct $createStruct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type
     */
    public function create( CreateStruct $createStruct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @param mixed $typeId
     * @param int $status
     * @param \eZ\Publish\SPI\Persistence\Content\Type\UpdateStruct $contentType
     *
     * @return Type
     */
    public function update( $typeId, $status, UpdateStruct $contentType )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @throws \eZ\Publish\API\Repository\Exceptions\BadStateException If type is defined and still has content
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If type is not found
     *
     * @param mixed $contentTypeId
     * @param int $status
     *
     * @return boolean
     */
    public function delete( $contentTypeId, $status )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a draft of existing defined content type
     *
     * Updates modified date, sets $modifierId and status to Type::STATUS_DRAFT on the new returned draft.
     *
     * @param mixed $modifierId
     * @param mixed $contentTypeId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If type with defined status is not found
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type
     */
    public function createDraft( $modifierId, $contentTypeId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @param mixed $userId
     * @param mixed $contentTypeId
     * @param int $status One of Type::STATUS_DEFINED|Type::STATUS_DRAFT|Type::STATUS_MODIFIED
     *
     * @return Type
     */
    public function copy( $userId, $contentTypeId, $status )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Unlink a content type group from a content type
     *
     * @param mixed $groupId
     * @param mixed $contentTypeId
     * @param int $status
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If group or type with provided status is not found
     * @throws \eZ\Publish\API\Repository\Exceptions\BadStateException If $groupId is last group on $contentTypeId or
     *                                                                 not a group assigned to type
     * @todo Add throws for NotFound and BadState when group is not assigned to type
     */
    public function unlink( $groupId, $contentTypeId, $status )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Link a content type group with a content type
     *
     * @param mixed $groupId
     * @param mixed $contentTypeId
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If group or type with provided status is not found
     * @throws \eZ\Publish\API\Repository\Exceptions\BadStateException If type is already part of group
     * @todo Above throws are not implemented
     */
    public function link( $groupId, $contentTypeId, $status )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns field definition for the given field definition id
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If field definition is not found
     *
     * @param mixed $id
     * @param int $status One of Type::STATUS_DEFINED|Type::STATUS_DRAFT|Type::STATUS_MODIFIED
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition
     */
    public function getFieldDefinition( $id, $status )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Adds a new field definition to an existing Type.
     *
     * This method creates a new status of the Type with the $fieldDefinition
     * added. It does not update existing content objects depending on the
     * field (default) values.
     *
     * @param mixed $contentTypeId
     * @param int $status One of Type::STATUS_DEFINED|Type::STATUS_DRAFT|Type::STATUS_MODIFIED
     * @param \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition $fieldDefinition
     *
     * @return void
     */
    public function addFieldDefinition( $contentTypeId, $status, FieldDefinition $fieldDefinition )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Removes a field definition from an existing Type.
     *
     * This method creates a new status of the Type with the field definition
     * referred to by $fieldDefinitionId removed. It does not update existing
     * content objects depending on the field (default) values.
     *
     * @param mixed $contentTypeId
     * @param mixed $fieldDefinitionId
     *
     * @return boolean
     */
    public function removeFieldDefinition( $contentTypeId, $status, $fieldDefinitionId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * This method updates the given $fieldDefinition on a Type.
     *
     * This method creates a new status of the Type with the updated
     * $fieldDefinition. It does not update existing content objects depending
     * on the
     * field (default) values.
     *
     * @param mixed $contentTypeId
     * @param \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition $fieldDefinition
     *
     * @return void
     */
    public function updateFieldDefinition( $contentTypeId, $status, FieldDefinition $fieldDefinition )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Update content objects
     *
     * Updates content objects, depending on the changed field definitions.
     *
     * A content type has a state which tells if its content objects yet have
     * been adapted.
     *
     * Flags the content type as updated.
     *
     * @param mixed $contentTypeId
     *
     * @return void
     */
    public function publish( $contentTypeId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
