<?php
/**
 * File containing FieldTypeService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package eZ\Publish\API\Repository
 */

namespace eZ\Publish\Core\Repository\Helper;

use eZ\Publish\SPI\FieldType\FieldType as SPIFieldType;
use eZ\Publish\Core\Base\Exceptions\NotFound\FieldTypeNotFoundException;
use RuntimeException;

/**
 * Registry for SPI FieldTypes
 */
class FieldTypeRegistry
{
    /**
     * @var \eZ\Publish\SPI\FieldType\FieldType[] Hash of SPI FieldTypes where key is identifier
     */
    protected $fieldTypes;

    /**
     * @param \eZ\Publish\SPI\FieldType\FieldType[]|\Closure $fieldTypes Hash of SPI FieldTypes where key is identifier
     */
    public function __construct( array $fieldTypes = array() )
    {
        $this->fieldTypes = $fieldTypes;
    }

    /**
     * Returns a list of all SPI FieldTypes.
     *
     * @return \eZ\Publish\SPI\FieldType\FieldType[]
     */
    public function getFieldTypes()
    {
        // First make sure all items are correct type (call closures)
        foreach ( $this->fieldTypes as $identifier => $value )
        {
            $this->initializeFieldType( $identifier );
        }
        return $this->fieldTypes;
    }

    /**
     * Return a SPI FieldType object
     *
     * @throws \eZ\Publish\Core\Base\Exceptions\NotFound\FieldTypeNotFoundException If $identifier was not found
     *
     * @param string $identifier
     *
     * @return \eZ\Publish\SPI\FieldType\FieldType
     */
    public function getFieldType( $identifier )
    {
        if ( !isset( $this->fieldTypes[$identifier] ) )
        {
            throw new FieldTypeNotFoundException( $identifier );
        }

        $this->initializeFieldType( $identifier );

        return $this->fieldTypes[$identifier];
    }

    /**
     * Initializes the FieldType Closure if needed and checks for the correct type
     *
     * @throws \RuntimeException If FieldType is not an instance of SPI FieldType interface,
     *                           or a closure returning the same
     *
     * @param string $identifier
     */
    protected function initializeFieldType( $identifier )
    {
        // First check for Closure and set the return value if found
        if ( is_callable( $this->fieldTypes[$identifier] ) )
        {
            /** @var $closure \Closure */
            $closure = $this->fieldTypes[$identifier];
            $this->fieldTypes[$identifier] = $closure();
        }

        // Check for implementation of correct interface
        if ( !$this->fieldTypes[$identifier] instanceof SPIFieldType )
        {
            throw new RuntimeException(
                "\$fieldTypes[$identifier] must be instance of SPI\\FieldType\\FieldType or callable"
            );
        }
    }

    /**
     * Returns if there is a SPI FieldType registered under $identifier
     *
     * @param string $identifier
     *
     * @return boolean
     */
    public function hasFieldType( $identifier )
    {
        return isset( $this->fieldTypes[$identifier] );
    }
}
