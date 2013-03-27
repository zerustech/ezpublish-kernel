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
use eZ\Publish\Core\Persistence\SqlNg\Content\StorageField;

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
    protected function getFieldHandler()
    {
        return $this->getPersistenceHandler()->fieldHandler();
    }

    public function testCtor()
    {
        $converter = $this->getFieldHandler();
        $this->assertInstanceOf(
            'eZ\\Publish\\Core\\Persistence\\SqlNg\\Content\\FieldHandler',
            $converter
        );
    }

    public function testCreateStorageFields()
    {
        $converter = $this->getFieldHandler();

        $contentType = $this->getContentType();
        $content = $this->getContent();

        $convertedFields = $converter->createStorageFields( $content->fields, $contentType->id );

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
        $converter = $this->getFieldHandler();

        $storageFields = $testData['fields'];

        $fields = $converter->extractFields( $storageFields );

        $this->assertCount( count( $storageFields ), $fields );
    }

    public function testUpdateFieldsToNewContentType()
    {
        $converter = $this->getFieldHandler();

        $contentType = $this->getContentType();
        $content = $this->getContent();

        $content->fields = $converter->createStorageFields( $content->fields, $contentType->id );

        $storageFieldsBeforeUpdate = $this->cloneArray( $content->fields );

        $content->fields[] = new StorageField(
            array(
                'field' => new Field(),
                'fieldDefinitionIdentifier' => 'removed-from-type',
            )
        );

        $converter->updateFieldsToNewContentType( $content );

        $this->assertEquals(
            $storageFieldsBeforeUpdate,
            $content->fields
        );
    }

    public function testCompleteFieldsByContentType()
    {
        $converter = $this->getFieldHandler();

        $contentType = $this->getContentType();
        $content = $this->getContent();

        $storageFields = $converter->createStorageFields( $content->fields, $contentType->id );

        $beforeCount = count( $storageFields );

        $completeStorageFields = $converter->completeFieldsByContentType(
            $storageFields,
            $contentType->id,
            array( $this->getLanguage()->id, $this->getSecondLanguage()->id )
        );

        $this->assertCount(
            $beforeCount * 2,
            $completeStorageFields
        );
    }

    protected function cloneArray( array $array )
    {
        $clonedArray = array();

        foreach ( $array as $key => $value )
        {
            if ( is_object( $value ) )
            {
                $clonedArray[$key] = clone $value;
            }
            else
            {
                $clonedArray[$key] = $value;
            }
        }

        return $clonedArray;
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

    /**
     * Force re-loading of content
     */
    protected function getContent()
    {
        $cachedContent = parent::getContent();

        return $this->getPersistenceHandler()->contentHandler()->load(
            $cachedContent->versionInfo->contentInfo->id,
            $cachedContent->versionInfo->versionNo
        );
    }
}
