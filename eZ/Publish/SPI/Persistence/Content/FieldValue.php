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
     * Either a scalar (primitive), null or an array (map) of scalar values.
     *
     * @note: For the legacy storage engine we will need adaptors to map them to
     * the existing database fields, like data_int, data_float, data_text.
     *
     * @var int|float|bool|string|null|array
     */
    public $data;

    /**
     * Mixed external field data
     *
     * Data which is not stored in the field but at an external place.
     * This data is processed by the field type storage interface method
     * storeFieldData, if used by the FieldType, otherwise null.
     *
     * Either a scalar (primitive), null or an array (map) of scalar values.
     *
     * @var int|float|bool|string|null|array
     */
    public $externalData;

    /**
     * A value which can be used for sorting
     *
     * @note: For the "old" storage engine we will need adaptors to map them to
     * the existing database fields, like sort_key_int, sort_key_string
     *
     * @var int|float|bool|string|null
     */
    public $sortKey;
}
