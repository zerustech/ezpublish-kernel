<?php
/**
 * File containing the EzcDatabase content gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Gateway;

use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;

use eZ\Publish\Core\Persistence\SqlNg\Content\Gateway;
use eZ\Publish\Core\Persistence\SqlNg\Content\Gateway\EzcDatabase\QueryBuilder;
use eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldValue;
use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Persistence\Content\CreateStruct;
use eZ\Publish\SPI\Persistence\Content\UpdateStruct;
use eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct;
use eZ\Publish\SPI\Persistence\Content\ContentInfo;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\Relation\CreateStruct as RelationCreateStruct;
use eZ\Publish\SPI\Persistence\Content\Language\Handler as LanguageHandler;
use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;
use eZ\Publish\API\Repository\Values\Content\VersionInfo as APIVersionInfo;
use ezcQueryUpdate;

/**
 * ezcDatabase based content gateway
 */
class EzcDatabase extends Gateway
{
    /**
     * Zeta Components database handler.
     *
     * @var \EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Query builder.
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway\EzcDatabase\QueryBuilder
     */
    protected $queryBuilder;

    /**
     * Caching language handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\CachingHandler
     */
    protected $languageHandler;

    /**
     * Creates a new gateway based on $db
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $dbHandler
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway\EzcDatabase\QueryBuilder $queryBuilder
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Language\CachingHandler $languageHandler
     */
    public function __construct(
        EzcDbHandler $dbHandler,
        QueryBuilder $queryBuilder,
        LanguageHandler $languageHandler )
    {
        $this->dbHandler = $dbHandler;
        $this->queryBuilder = $queryBuilder;
        $this->languageHandler = $languageHandler;
    }

    /**
     * Inserts a new content object.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\CreateStruct $struct
     * @param mixed $currentVersionNo
     *
     * @return int ID
     */
    public function insertContentObject( CreateStruct $struct, $currentVersionNo = 1 )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezcontent' )
        )->set(
            $this->dbHandler->quoteColumn( 'id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezcontent', 'id' )
        )->set(
            $this->dbHandler->quoteColumn( 'current_version_no' ),
            $query->bindValue( $currentVersionNo, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'name_list' ),
            $query->bindValue( json_encode( $struct->name ), null, \PDO::PARAM_STR )
        )->set(
            $this->dbHandler->quoteColumn( 'contenttype_id' ),
            $query->bindValue( $struct->typeId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'section_id' ),
            $query->bindValue( $struct->sectionId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'owner_id' ),
            $query->bindValue( $struct->ownerId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'initial_language_id' ),
            $query->bindValue( $struct->initialLanguageId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'always_available' ),
            $query->bindValue( $struct->alwaysAvailable, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'remote_id' ),
            $query->bindValue( $struct->remoteId, null, \PDO::PARAM_STR )
        )->set(
            $this->dbHandler->quoteColumn( 'modified' ),
            $query->bindValue( 0, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'published' ),
            $query->bindValue( 0, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'status' ),
            $query->bindValue( ContentInfo::STATUS_DRAFT, null, \PDO::PARAM_INT )
        );

        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezcontent', 'id' )
        );
    }

    /**
     * Inserts a new version.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     * @param \eZ\Publish\SPI\Persistence\Content\Field[] $fields
     *
     * @return int ID
     */
    public function insertVersion( VersionInfo $versionInfo, array $fields )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezcontent_version' )
        )->set(
            $this->dbHandler->quoteColumn( 'id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezcontent_version', 'id' )
        )->set(
            $this->dbHandler->quoteColumn( 'content_id' ),
            $query->bindValue( $versionInfo->contentInfo->id, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'version_no' ),
            $query->bindValue( $versionInfo->versionNo, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'modified' ),
            $query->bindValue( $versionInfo->modificationDate, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'creator_id' ),
            $query->bindValue( $versionInfo->creatorId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'created' ),
            $query->bindValue( $versionInfo->creationDate, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'status' ),
            $query->bindValue( $versionInfo->status, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'initial_language_id' ),
            $query->bindValue( $this->languageHandler->loadByLanguageCode( $versionInfo->initialLanguageCode )->id, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'fields' ),
            $query->bindValue( json_encode( $fields ), null, \PDO::PARAM_STR )
        );

        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezcontent_version', 'id' )
        );
    }

    /**
     * Updates an existing content identified by $contentId in respect to $struct
     *
     * @param int $contentId
     * @param \eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct $struct
     *
     * @return void
     */
    public function updateContent( $contentId, MetadataUpdateStruct $struct )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update( $this->dbHandler->quoteTable( 'ezcontent' ) );

        if ( isset( $struct->mainLanguageId ) )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'initial_language_id' ),
                $query->bindValue( $struct->mainLanguageId, null, \PDO::PARAM_INT )
            );
        }
        if ( isset( $struct->modificationDate ) )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'modified' ),
                $query->bindValue( $struct->modificationDate, null, \PDO::PARAM_INT )
            );
        }
        if ( isset( $struct->ownerId ) )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'owner_id' ),
                $query->bindValue( $struct->ownerId, null, \PDO::PARAM_INT )
            );
        }
        if ( isset( $struct->publicationDate ) )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'published' ),
                $query->bindValue( $struct->publicationDate, null, \PDO::PARAM_INT )
            );
        }
        if ( isset( $struct->remoteId ) )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'remote_id' ),
                $query->bindValue( $struct->remoteId, null, \PDO::PARAM_STR )
            );
        }
        if ( isset( $struct->alwaysAvailable ) )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'always_available' ),
                $query->bindValue( $struct->alwaysAvailable, null, \PDO::PARAM_STR )
            );
        }

        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'id' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        );
        $query->prepare()->execute();
    }

    /**
     * Updates version $versionNo for content identified by $contentId, in respect to $struct
     *
     * @param int $contentId
     * @param int $versionNo
     * @param \eZ\Publish\SPI\Persistence\Content\UpdateStruct $struct
     *
     * @return void
     */
    public function updateVersion( $contentId, $versionNo, UpdateStruct $struct )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent_version' )
        )->set(
            $this->dbHandler->quoteColumn( 'creator_id' ),
            $query->bindValue( $struct->creatorId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'modified' ),
            $query->bindValue( $struct->modificationDate, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'initial_language_id' ),
            $query->bindValue( $struct->initialLanguageId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'fields' ),
            $query->bindValue( json_encode( $struct->fields ), null, \PDO::PARAM_STR )
        )->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id' ),
                    $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'version_no' ),
                    $query->bindValue( $versionNo, null, \PDO::PARAM_INT )
                )
            )
        );
        $query->prepare()->execute();
    }

    /**
     * Updates "always available" flag for content identified by $contentId, in respect to $alwaysAvailable.
     *
     * @param int $contentId
     * @param boolean $newAlwaysAvailable New "always available" value
     */
    public function updateAlwaysAvailableFlag( $contentId, $newAlwaysAvailable )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Sets the status of the version identified by $contentId and $version to $status.
     *
     * The $status can be one of STATUS_DRAFT, STATUS_PUBLISHED, STATUS_ARCHIVED
     *
     * @param int $contentId
     * @param int $version
     * @param int $status
     *
     * @return boolean
     */
    public function setStatus( $contentId, $version, $status )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent_version' )
        )->set(
            $this->dbHandler->quoteColumn( 'status' ),
            $query->bindValue( $status, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'modified' ),
            $query->bindValue( time(), null, \PDO::PARAM_INT )
        )->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id' ),
                    $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'version_no' ),
                    $query->bindValue( $version, null, \PDO::PARAM_INT )
                )
            )
        );
        $statement = $query->prepare();
        $statement->execute();

        if ( !$statement->rowCount() )
        {
            return false;
        }

        if ( $status !== APIVersionInfo::STATUS_PUBLISHED )
        {
            return true;
        }

        // If the version's status is PUBLISHED, we set the content to published status as well
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent' )
        )->set(
            $this->dbHandler->quoteColumn( 'status' ),
            $query->bindValue( ContentInfo::STATUS_PUBLISHED, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'current_version_no' ),
            $query->bindValue( $version, null, \PDO::PARAM_INT )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'id' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        );
        $statement = $query->prepare();
        $statement->execute();

        return (bool)$statement->rowCount();
    }

    /**
     * Inserts a new field.
     *
     * Only used when a new field is created (i.e. a new object or a field in a
     * new language!). After that, field IDs need to stay the same, only the
     * version number changes.
     *
     * @param \eZ\Publish\SPI\Persistence\Content $content
     * @param \eZ\Publish\SPI\Persistence\Content\Field $field
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldValue $value
     *
     * @return int ID
     */
    public function insertNewField( Content $content, Field $field, StorageFieldValue $value )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Inserts an existing field.
     *
     * Used to insert a field with an exsting ID but a new version number.
     *
     * @param Content $content
     * @param Field $field
     * @param StorageFieldValue $value
     *
     * @return void
     */
    public function insertExistingField( Content $content, Field $field, StorageFieldValue $value )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates an existing field
     *
     * @param Field $field
     * @param StorageFieldValue $value
     *
     * @return void
     */
    public function updateField( Field $field, StorageFieldValue $value )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates an existing, non-translatable field
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Field $field
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldValue $value
     * @param int $contentId
     *
     * @return void
     */
    public function updateNonTranslatableField(
        Field $field,
        StorageFieldValue $value,
        $contentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads data for a content object
     *
     * Returns an array with the relevant data.
     *
     * @param mixed $contentId
     * @param mixed $version
     * @param string[] $translations
     *
     * @return array
     */
    public function load( $contentId, $version, $translations = null )
    {
        $query = $this->queryBuilder->createFindQuery( $translations );
        $query->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'id', 'ezcontent' ),
                    $query->bindValue( $contentId )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'version_no', 'ezcontent_version' ),
                    $query->bindValue( $version )
                )
            )
        );
        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for the latest published version of the content identified by
     * $contentId
     *
     * @param mixed $contentId
     *
     * @return array
     */
    public function loadLatestPublishedData( $contentId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads info for content identified by $contentId.
     * Will basically return a hash containing all field values for ezcontent table plus some additional keys:
     *  - always_available => Boolean indicating if content's language mask contains alwaysAvailable bit field
     *  - main_language_code => Language code for main (initial) language. E.g. "eng-GB"
     *
     * @param int $contentId
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\NotFoundException
     *
     * @return array
     */
    public function loadContentInfo( $contentId )
    {
        /** @var $query \ezcQuerySelect */
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            "ezcontent.*",
            $this->dbHandler->aliasedColumn( $query, 'main_id', 'ezcontent_location' ),
            $this->dbHandler->aliasedColumn( $query, 'id', 'ezcontent_location' )
        )->from(
            $this->dbHandler->quoteTable( "ezcontent" )
        )->leftJoin(
            $this->dbHandler->quoteTable( "ezcontent_location" ),
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "content_id", "ezcontent_location" ),
                    $this->dbHandler->quoteColumn( "id", "ezcontent" )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "main_id", "ezcontent_location" ),
                    $this->dbHandler->quoteColumn( "id", "ezcontent_location" )
                )
            )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( "id", "ezcontent" ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        );
        $statement = $query->prepare();
        $statement->execute();
        $row = $statement->fetch( \PDO::FETCH_ASSOC );

        if ( empty( $row ) )
        {
            throw new NotFound( "content", $contentId );
        }

        return $row;
    }

    /**
     * Loads version info for content identified by $contentId and $versionNo.
     * Will basically return a hash containing all field values from ezcontent_version table plus following keys:
     *  - names => Hash of content object names. Key is the language code, value is the name.
     *  - languages => Hash of language ids. Key is the language code (e.g. "eng-GB"), value is the language numeric id without the always available bit.
     *  - initial_language_code => Language code for initial language in this version.
     *
     * @param int $contentId
     * @param int $versionNo
     *
     * @return array
     */
    public function loadVersionInfo( $contentId, $versionNo )
    {
        $query = $this->queryBuilder->createVersionInfoFindQuery();
        $query->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_version' ),
                    $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'version_no', 'ezcontent_version' ),
                    $query->bindValue( $versionNo, null, \PDO::PARAM_INT )
                )
            )
        );
        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Returns data for all versions with given status created by the given $userId
     *
     * @param int $userId
     * @param int $status
     *
     * @return string[][]
     */
    public function listVersionsForUser( $userId, $status = VersionInfo::STATUS_DRAFT )
    {
        $query = $this->queryBuilder->createVersionInfoFindQuery();
        $query->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'status', 'ezcontent_version' ),
                    $query->bindValue( $status, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'creator_id', 'ezcontent_version' ),
                    $query->bindValue( $userId, null, \PDO::PARAM_INT )
                )
            )
        )->groupBy(
            $this->dbHandler->quoteColumn( 'id', 'ezcontent_version' )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Returns all version data for the given $contentId
     *
     * @param mixed $contentId
     *
     * @return string[][]
     */
    public function listVersions( $contentId )
    {
        $query = $this->queryBuilder->createVersionInfoFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_version' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        )->groupBy(
            $this->dbHandler->quoteColumn( 'id', 'ezcontent_version' )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Returns last version number for content identified by $contentId
     *
     * @param int $contentId
     *
     * @return int
     */
    public function getLastVersionNumber( $contentId )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $query->expr->max( $this->dbHandler->quoteColumn( 'version_no' ) )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_version' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'content_id' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return (int)$statement->fetchColumn();
    }

    /**
     * Returns all IDs for locations that refer to $contentId
     *
     * @param int $contentId
     *
     * @return int[]
     */
    public function getAllLocationIds( $contentId )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'id' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_location' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'content_id' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_COLUMN );
    }

    /**
     * Returns all field IDs of $contentId grouped by their type.
     * If $versionNo is set only field IDs for that version are returned.
     *
     * @param int $contentId
     * @param int|null $versionNo
     *
     * @return int[][]
     */
    public function getFieldIdsByType( $contentId, $versionNo = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes relations to and from $contentId.
     * If $versionNo is set only relations for that version are deleted.
     *
     * @param int $contentId
     * @param int|null $versionNo
     *
     * @return void
     */
    public function deleteRelations( $contentId, $versionNo = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes the field with the given $fieldId
     *
     * @param int $fieldId
     * @param int $version
     *
     * @return void
     */
    public function deleteField( $fieldId, $version )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes all fields of $contentId in all versions.
     * If $versionNo is set only fields for that version are deleted.
     *
     * @param int $contentId
     * @param int|null $versionNo
     *
     * @return void
     */
    public function deleteFields( $contentId, $versionNo = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes all versions of $contentId.
     * If $versionNo is set only that version is deleted.
     *
     * @param int $contentId
     * @param int|null $versionNo
     *
     * @return void
     */
    public function deleteVersion( $contentId, $versionNo )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom( 'ezcontent_version' )
            ->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'content_id' ),
                        $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                    ),
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'version_no' ),
                        $query->bindValue( $versionNo, null, \PDO::PARAM_INT )
                    )
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'content-version', "$contentId/$versionNo" );
        }
    }

    /**
     * Deletes all names of $contentId.
     * If $versionNo is set only names for that version are deleted.
     *
     * @param int $contentId
     * @param int|null $versionNo
     *
     * @return void
     */
    public function deleteNames( $contentId, $versionNo = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Sets the name for Content $contentId in version $version to $name in $language
     *
     * @param int $contentId
     * @param int $version
     * @param string $name
     * @param string $language
     *
     * @return void
     */
    public function setName( $contentId, $version, $name, $language )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes the actual content object referred to by $contentId
     *
     * @param int $contentId
     *
     * @return void
     */
    public function deleteContent( $contentId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom( 'ezcontent' )
            ->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'id' ),
                    $query->bindValue( $contentId, null, \PDO::PARAM_INT )
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'content', $contentId );
        }
    }

    /**
     * Loads relations from $contentId to published content, optionally only from $contentVersionNo.
     *
     * $relationType can also be filtered.
     *
     * @param int $contentId
     * @param int $contentVersionNo
     * @param int $relationType
     *
     * @return string[][] array of relation data
     */
    public function loadRelations( $contentId, $contentVersionNo = null, $relationType = null )
    {
        $query = $this->queryBuilder->createRelationFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'from_content_id', 'ezcontent_relation' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        );

        // source version number
        if ( isset( $contentVersionNo ) )
        {
            $query->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'from_content_version_no', 'ezcontent_relation' ),
                    $query->bindValue( $contentVersionNo, null, \PDO::PARAM_INT  )
                )
            );
        }
        // from published version only
        else
        {
            $query->from(
                $this->dbHandler->quoteTable( 'ezcontent' )
            )->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'id', 'ezcontent' ),
                        $this->dbHandler->quoteColumn( 'from_content_id', 'ezcontent_relation' )
                    ),
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'current_version_no', 'ezcontent' ),
                        $this->dbHandler->quoteColumn( 'from_content_version_no', 'ezcontent_relation' )
                    )
                )
            );
        }

        // relation type
        if ( isset( $relationType ) )
        {
            $query->where(
                $query->expr->bitAnd(
                    $this->dbHandler->quoteColumn( 'relation_type', 'ezcontent_relation' ),
                    $query->bindValue( $relationType, null, \PDO::PARAM_INT )
                )
            );
        }

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data that related to $toContentId
     *
     * @param int $toContentId
     * @param int $relationType
     *
     * @return mixed[][] Content data, array structured like {@see \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway::load()}
     */
    public function loadReverseRelations( $toContentId, $relationType = null )
    {
        $query = $this->queryBuilder->createRelationFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'to_content_id', 'ezcontent_relation' ),
                $query->bindValue( $toContentId, null, \PDO::PARAM_INT )
            )
        );

        // ezcontentobject join
        $query->from(
            $this->dbHandler->quoteTable( 'ezcontent' )
        )->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'id', 'ezcontent' ),
                    $this->dbHandler->quoteColumn( 'from_content_id', 'ezcontent_relation' )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'current_version_no', 'ezcontent' ),
                    $this->dbHandler->quoteColumn( 'from_content_version_no', 'ezcontent_relation' )
                )
            )
        );

        // relation type
        if ( isset( $relationType ) )
        {
            $query->where(
                $query->expr->bitAnd(
                    $this->dbHandler->quoteColumn( 'relation_type', 'ezcontent_relation' ),
                    $query->bindValue( $relationType, null, \PDO::PARAM_INT )
                )
            );
        }

        $statement = $query->prepare();

        $statement->execute();
        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Inserts a new relation database record
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Relation\CreateStruct $createStruct
     *
     * @return int ID the inserted ID
     */
    public function insertRelation( RelationCreateStruct $createStruct )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezcontent_relation' )
        )->set(
            $this->dbHandler->quoteColumn( 'id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezcontent_relation', 'id' )
        )->set(
            $this->dbHandler->quoteColumn( 'from_content_id' ),
            $query->bindValue( $createStruct->sourceContentId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'from_content_version_no' ),
            $query->bindValue( $createStruct->sourceContentVersionNo, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'to_content_id' ),
            $query->bindValue( $createStruct->destinationContentId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'relation_type' ),
            $query->bindValue( $createStruct->type, null, \PDO::PARAM_INT )
        );

        if ( $createStruct->sourceFieldDefinitionId )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'contenttype_field_id' ),
                $query->bindValue( (int)$createStruct->sourceFieldDefinitionId, null, \PDO::PARAM_INT )
            );
        }

        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezcontent_relation', 'id' )
        );
    }

    /**
     * Deletes the relation with the given $relationId
     *
     * @param int $relationId
     *
     * @return void
     */
    public function deleteRelation( $relationId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom( 'ezcontent_relation' )
        ->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'id' ),
                $query->bindValue( $relationId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'relation', $relationId );
        }
    }
}
