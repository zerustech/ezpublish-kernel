<?php
/**
 * File containing the Mapper class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Type;

use eZ\Publish\SPI\Persistence\Content\Type;
use eZ\Publish\SPI\Persistence\Content\Type\CreateStruct;
use eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition;
use eZ\Publish\SPI\Persistence\Content\Type\Group;
use eZ\Publish\SPI\Persistence\Content\Type\Group\CreateStruct as GroupCreateStruct;
use eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldDefinition;
use eZ\Publish\Core\Persistence\SqlNg\Content\FieldValue\ConverterRegistry as ConverterRegistry;

/**
 * Mapper for Content Type Handler.
 *
 * Performs mapping of Type objects.
 */
class Mapper
{
    /**
     * Converter registry
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\FieldValue\ConverterRegistry
     */
    protected $converterRegistry;

    /**
     * Creates a new content type mapper
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\FieldValue\ConverterRegistry $converterRegistry
     */
    public function __construct( ConverterRegistry $converterRegistry )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a Group from its create struct.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type\Group\CreateStruct $struct
     *
     * @todo $description is not supported by database, yet
     *
     * @return Group
     */
    public function createGroupFromCreateStruct( GroupCreateStruct $struct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts Group objects from the given $rows.
     *
     * @param array $rows
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type\Group[]
     */
    public function extractGroupsFromRows( array $rows )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts types and related data from the given $rows.
     *
     * @param array $rows
     *
     * @return array(Type)
     */
    public function extractTypesFromRows( array $rows )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a FieldDefinition from the data in $row.
     *
     * @param array $row
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition
     */
    public function extractFieldFromRow( array $row )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Maps properties from $struct to $type.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type\CreateStruct $createStruct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type
     */
    public function createTypeFromCreateStruct( CreateStruct $createStruct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a create struct from an existing $type.
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type $type
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Type\CreateStruct
     */
    public function createCreateStructFromType( Type $type )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Maps $fieldDef to the legacy storage specific StorageFieldDefinition
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition $fieldDef
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldDefinition $storageFieldDef
     *
     * @return void
     */
    public function toStorageFieldDefinition(
        FieldDefinition $fieldDef, StorageFieldDefinition $storageFieldDef )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Maps a FieldDefinition from the given $storageFieldDef
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldDefinition $storageFieldDef
     * @param \eZ\Publish\SPI\Persistence\Content\Type\FieldDefinition $fieldDef
     *
     * @return void
     */
    public function toFieldDefinition(
        StorageFieldDefinition $storageFieldDef, FieldDefinition $fieldDef )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
