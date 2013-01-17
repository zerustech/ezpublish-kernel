<?php
/**
 * File containing the Mapper class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content;

use eZ\Publish\SPI\Persistence;

/**
 * Mapper for Content Handler.
 *
 * Performs mapping of Content objects.
 */
class Mapper
{
    /**
     * Caching language handler
     *
     * @var \eZ\Publish\SPI\Persistence\Content\Language\Handler
     */
    protected $languageHandler;

    /**
     * Creates a new mapper.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Language\Handler $languageHandler
     */
    public function __construct( Persistence\Content\Language\Handler $languageHandler )
    {
        $this->languageHandler = $languageHandler;
    }

    /**
     * Creates a Content from the given $struct and $currentVersionNo
     *
     * @param \eZ\Publish\SPI\Persistence\Content\CreateStruct $struct
     * @param mixed $currentVersionNo
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ContentInfo
     */
    private function createContentInfoFromCreateStruct( Persistence\Content\CreateStruct $struct, $currentVersionNo = 1 )
    {
        $contentInfo = new Persistence\Content\ContentInfo;

        $contentInfo->id = null;
        $contentInfo->contentTypeId = $struct->typeId;
        $contentInfo->sectionId = $struct->sectionId;
        $contentInfo->ownerId = $struct->ownerId;
        $contentInfo->alwaysAvailable = $struct->alwaysAvailable;
        $contentInfo->remoteId = $struct->remoteId;
        $contentInfo->mainLanguageCode = $this->languageHandler->load( $struct->initialLanguageId )->languageCode;
        $contentInfo->name = isset( $struct->name[$contentInfo->mainLanguageCode] )
            ? $struct->name[$contentInfo->mainLanguageCode]
            : "";
        // For drafts published and modified timestamps should be 0
        $contentInfo->publicationDate = 0;
        $contentInfo->modificationDate = 0;
        $contentInfo->currentVersionNo = $currentVersionNo;
        $contentInfo->isPublished = false;

        return $contentInfo;
    }

    /**
     * Creates a new version for the given $struct and $versionNo
     *
     * @param \eZ\Publish\SPI\Persistence\Content\CreateStruct $struct
     * @param mixed $versionNo
     *
     * @return \eZ\Publish\SPI\Persistence\Content\VersionInfo
     */
    public function createVersionInfoFromCreateStruct( Persistence\Content\CreateStruct $struct, $versionNo )
    {
        $versionInfo = new Persistence\Content\VersionInfo();

        $versionInfo->id = null;
        $versionInfo->contentInfo = $this->createContentInfoFromCreateStruct( $struct, $versionNo );
        $versionInfo->versionNo = $versionNo;
        $versionInfo->creatorId = $struct->ownerId;
        $versionInfo->status = Persistence\Content\VersionInfo::STATUS_DRAFT;
        $versionInfo->initialLanguageCode = $this->languageHandler->load( $struct->initialLanguageId )->languageCode;
        $versionInfo->creationDate = $struct->modified;
        $versionInfo->modificationDate = $struct->modified;
        $versionInfo->names = $struct->name;

        $languageIds = array();
        foreach ( $struct->fields as $field )
        {
            if ( !isset( $languageIds[$field->languageCode] ) )
            {
                $languageIds[$field->languageCode] =
                    $this->languageHandler->loadByLanguageCode( $field->languageCode )->id;
            }
        }
        $versionInfo->languageIds = array_values( $languageIds );

        return $versionInfo;
    }

    /**
     * Creates a new version for the given $content
     *
     * @param \eZ\Publish\SPI\Persistence\Content $content
     * @param mixed $versionNo
     * @param mixed $userId
     *
     * @return \eZ\Publish\SPI\Persistence\Content\VersionInfo
     */
    public function createVersionInfoForContent( Content $content, $versionNo, $userId )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Converts value of $field to storage value
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Field $field
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldValue
     */
    public function convertToStorageValue( Field $field )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts Content objects (and nested) from database result $rows
     *
     * Expects database rows to be indexed by keys of the format
     *
     *      "$tableName_$columnName"
     *
     * @param array $rows
     *
     * @return \eZ\Publish\SPI\Persistence\Content[]
     */
    public function extractContentFromRows( array $rows )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts a ContentInfo object from $row
     *
     * @param array $row
     * @param string $prefix Prefix for row keys, which are initially mapped by ezcontentobject fields
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ContentInfo
     */
    public function extractContentInfoFromRow( array $row, $prefix = '' )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts a VersionInfo object from $row.
     *
     * This method will return VersionInfo with incomplete data. It is intended to be used only by
     * {@link self::extractContentFromRows} where missing data will be filled in.
     *
     * @param array $row
     *
     * @return \eZ\Publish\SPI\Persistence\Content\VersionInfo
     */
    private function extractVersionInfoFromRow( array $row )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts a VersionInfo object from $row
     *
     * @param array $rows
     *
     * @return \eZ\Publish\SPI\Persistence\Content\VersionInfo[]
     */
    public function extractVersionInfoListFromRows( array $rows )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * @param int $languageMask
     *
     * @return array
     */
    public function extractLanguageIdsFromMask( $languageMask )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates CreateStruct from $content
     *
     * @param \eZ\Publish\SPI\Persistence\Content $content
     *
     * @return \eZ\Publish\SPI\Persistence\Content\CreateStruct
     */
    public function createCreateStructFromContent( Content $content )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts relation objects from $rows
     */
    public function extractRelationsFromRows( array $rows )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a Content from the given $struct
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Relation\CreateStruct $struct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Relation
     */
    public function createRelationFromCreateStruct( RelationCreateStruct $struct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
