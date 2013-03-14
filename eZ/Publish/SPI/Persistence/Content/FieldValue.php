<?php
/**
 * File containing the (content) FieldValue class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\SPI\Persistence\Content;

use eZ\Publish\SPI\Persistence\ValueObject;

/**
 */
class FieldValue extends ValueObject
{
    /**
     * Mixed field data
     *
     * Either a primitive, an array (map) or an object
     * If object it *must* be serializable, for instance DOMDocument is not valid object.
     *
     * @note: For the legacy storage engine we will need adaptors to map them to
     * the existing database fields, like data_int, data_float, data_text.
     *
     * @var mixed
     */
    public $data;

    /**
     * Mixed external field data
     *
     * Data which is not stored in the field but at an external place.
     * This data is processed by the field type storage interface method
     * storeFieldData, if used by the FieldType, otherwise null.
     *
     * Either a primitive, an array (map) or an object
     * If object it *must* be serializable, for instance DOMDocument is not valid object.
     *
     * @var mixed
     */
    public $externalData;

    /**
     * A value which can be used for sorting
     *
     * @note: For the "old" storage engine we will need adaptors to map them to
     * the existing database fields, like sort_key_int, sort_key_string
     * Value *must* be serializable.
     *
     * @var mixed
     */
    public $sortKey;
}
