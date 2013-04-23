<?php
/**
 * File containing the Content Gateway base class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content;

use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Persistence\Content\CreateStruct;
use eZ\Publish\SPI\Persistence\Content\UpdateStruct;
use eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\Relation\CreateStruct as RelationCreateStruct;

/**
 * Base class for content gateways
 */
abstract class Gateway
{
    /**
     * Inserts a new content object.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\CreateStruct $struct
     * @param mixed $currentVersionNo
     *
     * @return int ID
     */
    abstract public function insertContentObject( CreateStruct $struct, $currentVersionNo = 1 );

    /**
     * Inserts a new version.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     * @param \eZ\Publish\SPI\Persistence\Content\Field[] $fields
     *
     * @return int ID
     */
    abstract public function insertVersion( VersionInfo $versionInfo, array $fields );

    /**
     * Updates an existing content identified by $contentId in respect to $struct
     *
     * @param int $contentId
     * @param \eZ\Publish\SPI\Persistence\Content\MetadataUpdateStruct $struct
     *
     * @return void
     */
    abstract public function updateContent( $contentId, MetadataUpdateStruct $struct );

    /**
     * Updates version $versionNo for content identified by $contentId, in respect to $struct
     *
     * @param int $contentId
     * @param int $versionNo
     * @param \eZ\Publish\SPI\Persistence\Content\UpdateStruct $struct
     *
     * @return void
     */
    abstract public function updateVersion( $contentId, $versionNo, UpdateStruct $struct );

    /**
     * Updates $fields for $versionNo of content identified by $contentId
     *
     * @param int $contentId
     * @param int $versionNo
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\StorageField[] $fields
     *
     * @return void
     */
    abstract public function updateFields( $contentId, $versionNo, array $fields );

    /**
     * Sets the state of object identified by $contentId and $version to $state.
     *
     * The $status can be one of STATUS_DRAFT, STATUS_PUBLISHED, STATUS_ARCHIVED
     *
     * @param int $contentId
     * @param int $version
     * @param int $status
     *
     * @return boolean
     */
    abstract public function setStatus( $contentId, $version, $status );

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
    abstract public function load( $contentId, $version, $translations = null );

    /**
     * Loads info for content identified by $contentId.
     * Will basically return a hash containing all field values for ezcontentobject table plus following keys:
     *  - always_available => Boolean indicating if content's language mask contains alwaysAvailable bit field
     *  - main_language_code => Language code for main (initial) language. E.g. "eng-GB"
     *
     * @param int $contentId
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\NotFoundException
     *
     * @return array
     */
    abstract public function loadContentInfo( $contentId );

    /**
     * Loads version info for content identified by $contentId and $versionNo.
     * Will basically return a hash containing all field values from ezcontentobject_version table plus following keys:
     *  - names => Hash of content object names. Key is the language code, value is the name.
     *  - languages => Hash of language ids. Key is the language code (e.g. "eng-GB"), value is the language numeric id without the always available bit.
     *  - initial_language_code => Language code for initial language in this version.
     *
     * @param int $contentId
     * @param int $versionNo
     *
     * @return array
     */
    abstract public function loadVersionInfo( $contentId, $versionNo );

    /**
     * Returns data for all versions with given status created by the given $userId
     *
     * @param int $userId
     * @param int $status
     *
     * @return string[][]
     */
    abstract public function listVersionsForUser( $userId, $status = VersionInfo::STATUS_DRAFT );

    /**
     * Returns all version data for the given $contentId
     *
     * @param mixed $contentId
     *
     * @return string[][]
     */
    abstract public function listVersions( $contentId );

    /**
     * Returns last version number for content identified by $contentId
     *
     * @param int $contentId
     *
     * @return int
     */
    abstract public function getLastVersionNumber( $contentId );

    /**
     * Returns all IDs for locations that refer to $contentId
     *
     * @param int $contentId
     *
     * @return int[]
     */
    abstract public function getAllLocationIds( $contentId );

    /**
     * Deletes all versions of $contentId.
     * If $versionNo is set only that version is deleted.
     *
     * @param int $contentId
     * @param int|null $versionNo
     *
     * @return void
     */
    abstract public function deleteVersion( $contentId, $versionNo );

    /**
     * Deletes the actual content object referred to by $contentId
     *
     * @param int $contentId
     *
     * @return void
     */
    abstract public function deleteContent( $contentId );

    /**
     * Loads data of related to/from $contentId
     *
     * @param int $contentId
     * @param int $contentVersionNo
     * @param int $relationType
     *
     * @return mixed[][] Content data, array structured like {@see \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway::load()}
     */
    abstract public function loadRelations( $contentId, $contentVersionNo = null, $relationType = null );

    /**
     * Loads data that related to $toContentId
     *
     * @param int $toContentId
     * @param int $relationType
     *
     * @return mixed[][] Content data, array structured like {@see \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway::load()}
     */
    abstract public function loadReverseRelations( $toContentId, $relationType = null );

    /**
     * Inserts a new relation database record
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Relation\CreateStruct $createStruct
     *
     * @return int ID the inserted ID
     */
    abstract public function insertRelation( RelationCreateStruct $createStruct );

    /**
     * Deletes the relation with the given $relationId
     *
     * @param int $relationId
     *
     * @return void
     */
    abstract public function deleteRelation( $relationId );
}
