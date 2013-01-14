<?php
/**
 * File containing the Language Mapper class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Language;

use eZ\Publish\SPI\Persistence\Content\Language;
use eZ\Publish\SPI\Persistence\Content\Language\CreateStruct;

/**
 * Language Mapper
 */
class Mapper
{
    /**
     * Creates a Language from $struct
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Language\CreateStruct $struct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language
     */
    public function createLanguageFromCreateStruct( CreateStruct $struct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts Language objects from $rows
     *
     * @param array $rows
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language[]
     */
    public function extractLanguagesFromRows( array $rows )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
