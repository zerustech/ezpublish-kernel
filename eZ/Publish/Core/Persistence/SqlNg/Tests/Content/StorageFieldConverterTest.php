<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\Content\ContentHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\Content;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;

use eZ\Publish\SPI\Persistence\Content\Field;
use eZ\Publish\SPI\Persistence\Content\Type;

/**
 * Test case for StorageField Handler
 */
class StorageFieldHandlerTest extends TestCase
{
    /**
     * Returns the converter to test
     *
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\StorageFieldHandler
     */
    protected function getStorageFieldConverter()
    {
        return $this->getPersistenceHandler()->storageFieldConverter();
    }

    public function testCtor()
    {
        $converter = $this->getStorageFieldConverter();
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\StorageFieldConverter',
            $converter
        );
    }

    public function testCreateStorageFields()
    {
        $converter = $this->getStorageFieldConverter();

        $contentType = $this->getContentType();

        $fields = $this->getFieldsFixture( $contentType );

        $convertedFields = $converter->createStorageFields( $fields, $contentType->id );

        $this->assertInternalType( 'array', $convertedFields );

        return array(
            'fields' => $convertedFields,
            'type' => $contentType,
        );
    }

    /**
     * @depends testCreateStorageFields
     */
    public function testCreateStorageFieldsCount( array $testData )
    {
        $storageFields = $testData['fields'];
        $contentType = $testData['type'];

        $this->assertCount( count( $contentType->fieldDefinitions ), $storageFields );
    }

    /**
     * @depends testCreateStorageFields
     */
    public function testCreateStorageFieldsType( array $testData )
    {
        $storageFields = $testData['fields'];

        foreach ( $storageFields as $storageField )
        {
            $this->assertInstanceOf(
                'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\StorageField',
                $storageField
            );
        }
    }

    /**
     * @depends testCreateStorageFields
     */
    public function testCreateStorageFieldsTypeIdentifier( array $testData )
    {
        $storageFields = $testData['fields'];
        $contentType = $testData['type'];

        $identifierMap = $this->getFieldTypeIdentiefierMap( $contentType );

        foreach ( $storageFields as $storageField )
        {
            $this->assertEquals(
                $identifierMap[$storageField->field->fieldDefinitionId],
                $storageField->fieldDefinitionIdentifier
            );
        }
    }

    /**
     * @depends testCreateStorageFields
     */
    public function testExtractFields( array $testData )
    {
        $converter = $this->getStorageFieldConverter();

        $storageFields = $testData['fields'];

        $fields = $converter->extractFields( $storageFields );

        $this->assertCount( count( $storageFields ), $fields );
    }

    protected function getFieldTypeIdentiefierMap( Type $type )
    {
        $identifierMap = array();
        foreach ( $type->fieldDefinitions as $fieldDefinition )
        {
            $identifierMap[$fieldDefinition->id] = $fieldDefinition->identifier;
        }
        return $identifierMap;
    }

    protected function getFieldsFixture( Type $type )
    {
        $fields = array();

        foreach ( $type->fieldDefinitions as $fieldDefinition )
        {
            $fields[] = new Field(
                array(
                    'fieldDefinitionId' => $fieldDefinition->id,
                )
            );
        }

        return $fields;
    }

    protected function getContentType()
    {
        $contentType = parent::getContentType();
        $this->getPersistenceHandler()->contentTypeHandler()->publish( $contentType->id );
        return $contentType;
    }
}
