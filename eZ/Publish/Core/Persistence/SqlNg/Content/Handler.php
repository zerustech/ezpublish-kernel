<?php
/**
 * File containing the Content Handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content;

use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway as LocationGateway;
use eZ\Publish\SPI\Persistence\Content\Handler as BaseContentHandler;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Persistence\Content\CreateStruct;
use eZ\Publish\SPI\Persistence\Content\UpdateStruct;
use eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use eZ\Publish\SPI\Persistence\Content\Relation\CreateStruct as RelationCreateStruct;
use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;

/**
 * The Content Handler stores Content and ContentType objects.
 */
class Handler implements BaseContentHandler
{
    /**
     * Content gateway.
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway
     */
    protected $contentGateway;

    /**
     * Location gateway.
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway
     */
    protected $locationGateway;

    /**
     * Mapper.
     *
     * @var Mapper
     */
    protected $mapper;

    /**
     * FieldHandler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\FieldHandler
     */
    protected $fieldHandler;

    /**
     * Creates a new content handler.
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway $contentGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway $locationGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Mapper $mapper
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\FieldHandler $fieldHandler
     */
    public function __construct(
        Gateway $contentGateway,
        LocationGateway $locationGateway,
        Mapper $mapper,
        FieldHandler $fieldHandler
    )
    {
        $this->contentGateway = $contentGateway;
        $this->locationGateway = $locationGateway;
        $this->mapper = $mapper;
        $this->fieldHandler = $fieldHandler;
    }

    /**
     * Creates a new Content entity in the storage engine.
     *
     * The values contained inside the $content will form the basis of stored
     * entity.
     *
     * Will contain always a complete list of fields.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\CreateStruct $struct Content creation struct.
     *
     * @return \eZ\Publish\SPI\Persistence\Content Content value object
     */
    public function create( CreateStruct $struct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Performs the publishing operations required to set the version identified by $updateStruct->versionNo and
     * $updateStruct->id as the published one.
     *
     * The publish procedure will:
     * - Create location nodes based on the node assignments
     * - Update the content object using the provided metadata update struct
     * - Update the node assignments
     * - Update location nodes of the content with the new published version
     * - Set content and version status to published
     *
     * @param int $contentId
     * @param int $versionNo
     * @param \eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct $metaDataUpdateStruct
     *
     * @return \eZ\Publish\SPI\Persistence\Content The published Content
     */
    public function publish( $contentId, $versionNo, MetadataUpdateStruct $metaDataUpdateStruct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a new draft version from $contentId in $version.
     *
     * Copies all fields from $contentId in $srcVersion and creates a new
     * version of the referred Content from it.
     *
     * Note: When creating a new draft in the old admin interface there will
     * also be an entry in the `eznode_assignment` created for the draft. This
     * is ignored in this implementation.
     *
     * @param mixed $contentId
     * @param mixed $srcVersion
     * @param mixed $userId
     *
     * @return \eZ\Publish\SPI\Persistence\Content
     */
    public function createDraftFromVersion( $contentId, $srcVersion, $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns the raw data of a content object identified by $id, in a struct.
     *
     * A version to load must be specified. If you want to load the current
     * version of a content object use SearchHandler::findSingle() with the
     * ContentId criterion.
     *
     * Optionally a translation filter may be specified. If specified only the
     * translations with the listed language codes will be retrieved. If not,
     * all translations will be retrieved.
     *
     * @param int|string $id
     * @param int|string $version
     * @param string[] $translations
     *
     * @return \eZ\Publish\SPI\Persistence\Content Content value object
     */
    public function load( $id, $version, $translations = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns the metadata object for a content identified by $contentId.
     *
     * @param int|string $contentId
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ContentInfo
     */
    public function loadContentInfo( $contentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns the version object for a content/version identified by $contentId and $versionNo
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If version is not found
     *
     * @param int|string $contentId
     * @param int $versionNo Version number to load
     *
     * @return \eZ\Publish\SPI\Persistence\Content\VersionInfo
     */
    public function loadVersionInfo( $contentId, $versionNo )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns all versions with draft status created by the given $userId
     *
     * @param int $userId
     *
     * @return \eZ\Publish\SPI\Persistence\Content\VersionInfo[]
     */
    public function loadDraftsForUser( $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Sets the status of object identified by $contentId and $version to $status.
     *
     * The $status can be one of VersionInfo::STATUS_DRAFT, VersionInfo::STATUS_PUBLISHED, VersionInfo::STATUS_ARCHIVED
     * When status is set to VersionInfo::STATUS_PUBLISHED content status is updated to ContentInfo::STATUS_PUBLISHED
     *
     * @param int $contentId
     * @param int $status
     * @param int $version
     *
     * @return boolean
     */
    public function setStatus( $contentId, $status, $version )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates a content object meta data, identified by $contentId
     *
     * @param int $contentId
     * @param \eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct $content
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ContentInfo
     */
    public function updateMetadata( $contentId, MetadataUpdateStruct $content )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates a content version, identified by $contentId and $versionNo
     *
     * @param int $contentId
     * @param int $versionNo
     * @param \eZ\Publish\SPI\Persistence\Content\UpdateStruct $updateStruct
     *
     * @return \eZ\Publish\SPI\Persistence\Content
     */
    public function updateContent( $contentId, $versionNo, UpdateStruct $updateStruct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes all versions and fields, all locations (subtree), and all relations.
     *
     * Removes the relations, but not the related objects. All subtrees of the
     * assigned nodes of this content objects are removed (recursively).
     *
     * @param int $contentId
     *
     * @return boolean
     */
    public function deleteContent( $contentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes raw content data
     *
     * @param int $contentId
     */
    public function removeRawContent( $contentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes given version, its fields, node assignment, relations and names.
     *
     * Removes the relations, but not the related objects.
     *
     * @param int $contentId
     * @param int $versionNo
     *
     * @return boolean
     */
    public function deleteVersion( $contentId, $versionNo )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns the versions for $contentId
     *
     * @param int $contentId
     *
     * @return \eZ\Publish\SPI\Persistence\Content\VersionInfo[]
     */
    public function listVersions( $contentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Copy Content with Fields and Versions from $contentId in $version.
     *
     * Copies all fields from $contentId in $versionNo (or all versions if null)
     * to a new object which is returned. Version numbers are maintained.
     *
     * @todo Should relations be copied? Which ones?
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If content or version is not found
     *
     * @param mixed $contentId
     * @param mixed|null $versionNo Copy all versions if left null
     *
     * @return \eZ\Publish\SPI\Persistence\Content
     */
    public function copy( $contentId, $versionNo = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a relation between $sourceContentId in $sourceContentVersionNo
     * and $destinationContentId with a specific $type.
     *
     * @todo Should the existence verifications happen here or is this supposed to be handled at a higher level?
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Relation\CreateStruct $createStruct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Relation
     */
    public function addRelation( RelationCreateStruct $createStruct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Removes a relation by $relationId.
     *
     * @param mixed $relationId
     *
     * @return void
     */
    public function removeRelation( $relationId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads relations from $sourceContentId. Optionally, loads only those with $type and $sourceContentVersionNo.
     *
     * @param mixed $sourceContentId Source Content ID
     * @param mixed|null $sourceContentVersionNo Source Content Version, null if not specified
     * @param int|null $type {@see \eZ\Publish\API\Repository\Values\Content\Relation::COMMON,
     *                 \eZ\Publish\API\Repository\Values\Content\Relation::EMBED,
     *                 \eZ\Publish\API\Repository\Values\Content\Relation::LINK,
     *                 \eZ\Publish\API\Repository\Values\Content\Relation::FIELD}
     * @return \eZ\Publish\SPI\Persistence\Content\Relation[]
     */
    public function loadRelations( $sourceContentId, $sourceContentVersionNo = null, $type = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads relations from $contentId. Optionally, loads only those with $type.
     *
     * Only loads relations against published versions.
     *
     * @param mixed $destinationContentId Destination Content ID
     * @param int|null $type {@see \eZ\Publish\API\Repository\Values\Content\Relation::COMMON,
     *                 \eZ\Publish\API\Repository\Values\Content\Relation::EMBED,
     *                 \eZ\Publish\API\Repository\Values\Content\Relation::LINK,
     *                 \eZ\Publish\API\Repository\Values\Content\Relation::FIELD}
     * @return \eZ\Publish\SPI\Persistence\Content\Relation[]
     */
    public function loadReverseRelations( $destinationContentId, $type = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
