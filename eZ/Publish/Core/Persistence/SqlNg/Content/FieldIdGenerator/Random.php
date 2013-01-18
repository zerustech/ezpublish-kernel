<?php
/**
 * File containing the Field ID Generator class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\FieldIdGenerator;

use eZ\Publish\Core\Persistence\SqlNg\Content\FieldIdGenerator;
use eZ\Publish\SPI\Persistence;

/**
 * The Content Handler stores Content and ContentType objects.
 */
class Random extends FieldIdGenerator
{
    /**
     * Generate field ID
     *
     * @param Persistence\Content\VersionInfo $versionInfo
     * @param Persistence\Content\Field $field
     * @return mixed
     */
    public function generateFieldId( Persistence\Content\VersionInfo $versionInfo, Persistence\Content\Field $field )
    {
        return mt_rand( 0, mt_getrandmax() );
    }
}
