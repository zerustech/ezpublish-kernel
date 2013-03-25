<?php

namespace eZ\Publish\Core\Persistence\SqlNg\Content;

use eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler as ContentTypeHandler;

use eZ\Publish\SPI\Persistence\Content\Type;
use eZ\Publish\SPI\Persistence\Content\Field;

class StorageFieldConverter
{
    /**
     * @var eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler
     */
    protected $contentTypeHandler;

    /**
     * @param eZ\Publish\Core\Persistence\SqlNg\Content\Type\Handler $contentTypeHandler
     */
    public function __construct( ContentTypeHandler $contentTypeHandler )
    {
        $this->contentTypeHandler = $contentTypeHandler;
    }

    /**
     * @param eZ\Publish\SPI\Persistence\Content\Field $fields
     * @return eZ\Publish\Core\Persistence\SqlNg\Content\StorageField
     */
    public function createStorageFields( array $fields, $contentTypeId )
    {
        $type = $this->contentTypeHandler->load( $contentTypeId );

        $storageFields = array();
        foreach ( $fields as $field )
        {
            $storageFields[] = $this->createStorageField( $type, $field );
        }
        return $storageFields;
    }

    /**
     * @param eZ\Publish\Core\Persistence\SqlNg\Content\StorageField $storageFields
     * @return eZ\Publish\SPI\Persistence\Content\Field
     */
    public function extractFields( array $storageFields )
    {
        $fields = array();
        foreach ( $storageFields as $storageField )
        {
            $fields[] = $storageField->field;
        }
        return $fields;
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\Type $type
     * @param \eZ\Publish\SPI\Persistence\Content\Field $field
     * @return \eZ\Publish\Core\Persistence\SqlNg\Content\StorageField
     */
    protected function createStorageField( Type $type, Field $field )
    {
        return new StorageField(
            array(
                'field' => $field,
                'fieldDefinitionIdentifier' => $this->getFieldDefinitionIdentifier( $type, $field->fieldDefinitionId ),
            )
        );
    }

    /**
     * @param \eZ\Publish\SPI\Persistence\Content\Type $contentType
     * @param mixed $fieldDefinitionId
     */
    protected function getFieldDefinitionIdentifier( Type $contentType, $fieldDefinitionId )
    {
        foreach ( $contentType->fieldDefinitions as $fieldDefinition )
        {
            if ( $fieldDefinitionId == $fieldDefinition->id )
            {
                return $fieldDefinition->identifier;
            }
        }

        throw new \RuntimeException(
            sprintf(
                'Could not find identifier for field definition "%s" in content type "%s".',
                $fieldDefinitionId,
                $contentType->id
            )
        );
    }
}
