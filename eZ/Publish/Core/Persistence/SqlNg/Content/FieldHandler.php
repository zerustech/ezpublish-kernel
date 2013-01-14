<?php
/**
 * File containing the Content FieldHandler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content;

use eZ\Publish\SPI\Persistence\Content;
use eZ\Publish\SPI\Persistence\Content\Type;
use eZ\Publish\SPI\Persistence\Content\VersionInfo;
use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\UpdateStruct;
use eZ\Publish\Core\Persistence\SqlNg\Content\Type\Gateway as TypeGateway;

/**
 * Field Handler.
 */
class FieldHandler
{
    /**
     * Content Gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway
     */
    protected $contentGateway;

    /**
     * Content Type Gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Gateway
     */
    protected $typeGateway;

    /**
     * Content Mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Mapper
     */
    protected $mapper;

    /**
     * Storage Handler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\StorageHandler
     */
    protected $storageHandler;

    /**
     * Creates a new Field Handler
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway $contentGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Type\Gateway $typeGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Mapper $mapper
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\StorageHandler $storageHandler
     */
    public function __construct(
        Gateway $contentGateway,
        TypeGateway $typeGateway,
        Mapper $mapper,
        StorageHandler $storageHandler )
    {
        $this->contentGateway = $contentGateway;
        $this->typeGateway = $typeGateway;
        $this->mapper = $mapper;
        $this->storageHandler = $storageHandler;
    }

    /**
     * Creates new fields in the database from $content
     *
     * @param \eZ\Publish\SPI\Persistence\Content $content
     *
     * @return void
     */
    public function createNewFields( Content $content )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates existing fields in a new version for $content
     *
     * @param \eZ\Publish\SPI\Persistence\Content $content
     *
     * @return void
     */
    public function createExistingFieldsInNewVersion( Content $content )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates an existing field in a new version, no new ID is generated
     *
     * @param Field $field
     * @param Content $content
     *
     * @return void
     */
    public function createExistingFieldInNewVersion( Field $field, Content $content )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Performs external loads for the fields in $content
     *
     * @param Content $content
     *
     * @return void
     */
    public function loadExternalFieldData( Content $content )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates the fields in for content identified by $contentId and $versionNo in the database in respect to $updateStruct
     *
     * @param \eZ\Publish\SPI\Persistence\Content $content
     * @param \eZ\Publish\SPI\Persistence\Content\UpdateStruct $updateStruct
     *
     * @return void
     */
    public function updateFields( $content, UpdateStruct $updateStruct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes the fields for $contentId in $versionInfo from the database
     *
     * @param int $contentId
     * @param \eZ\Publish\SPI\Persistence\Content\VersionInfo $versionInfo
     *
     * @return void
     */
    public function deleteFields( $contentId, VersionInfo $versionInfo )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
