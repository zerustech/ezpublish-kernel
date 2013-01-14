<?php
/**
 * File containing the UrlWildcard Mapper class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\UrlWildcard;

use eZ\Publish\SPI\Persistence\Content\UrlWildcard;

/**
 * UrlWildcard Mapper
 */
class Mapper
{
    /**
     * Creates a UrlWildcard object from given parameters
     *
     * @param string $sourceUrl
     * @param string $destinationUrl
     * @param boolean $forward
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlWildcard
     */
    public function createUrlWildcard( $sourceUrl, $destinationUrl, $forward )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts UrlWildcard object from given database $row
     *
     * @param array $row
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlWildcard
     */
    public function extractUrlWildcardFromRow( array $row )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Extracts UrlWildcard objects from database $rows
     *
     * @param array $rows
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlWildcard[]
     */
    public function extractUrlWildcardsFromRows( array $rows )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
