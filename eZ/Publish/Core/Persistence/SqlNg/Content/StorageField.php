<?php

namespace eZ\Publish\Core\Persistence\SqlNg\Content;

use eZ\Publish\SPI\Persistence\ValueObject;

class StorageField extends ValueObject
{
    /**
     * @var eZ\Publish\SPI\Persistence\Content\Field
     */
    public $field;

    /**
     * @var string
     */
    public $fieldDefinitionIdentifier;
}
