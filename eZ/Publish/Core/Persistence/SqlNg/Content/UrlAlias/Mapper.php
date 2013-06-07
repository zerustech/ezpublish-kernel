<?php
/**
 * File containing the UrlAlias Mapper class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias;

use eZ\Publish\SPI\Persistence\Content\UrlAlias;
use eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator as LanguageMaskGenerator;

/**
 * UrlAlias Mapper
 */
class Mapper
{
    /**
     * Creates a UrlAlias object from database row data
     *
     * @param mixed[] $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias
     */
    public function extractUrlAliasFromData( array $rows )
    {
        $aliases = $this->extractUrlAliasListFromData( $rows );

        if ( count( $aliases ) !==  1 ) {
            throw new \RuntimeException( "Invalid number of aliases." );
        }

        return reset( $aliases );
    }

    /**
     * Extracts UrlAlias objects from database $rows
     *
     * @param array $rows
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias[]
     */
    public function extractUrlAliasListFromData( array $rows )
    {
        $aliases = array();

        foreach ( $rows as $row )
        {
            if ( !isset( $aliases[$row['alias_id']] ) )
            {
                $aliases[$row['alias_id']] = $this->createUrlAliasFromRow( $row );
            }

            $aliases[$row['alias_id']]->languageCodes[] = $row['language_code'];
        }

        return array_values( $aliases );
    }

    /**
     * Create UrlAlias from row
     *
     * @param array $row
     * @return UrlAlias
     */
    protected function createUrlAliasFromRow( array $row )
    {
        $urlAlias = new UrlAlias();

        $urlAlias->id = $row['alias_id'];
        $urlAlias->type = (int) $row['type'];
        $urlAlias->destination = $row['destination'];
        $urlAlias->languageCodes = array();
        $urlAlias->alwaysAvailable = (bool) $row['always_available'];
        $urlAlias->forward = (bool) $row["forward"];
        $urlAlias->isHistory = (bool) $row["history"];
        $urlAlias->isCustom = (bool) $row["custom"];

        // @TODO: This is supposed to include languages somehow. I can't deduce
        // from the documentation in which way. Currently we are just returning
        // the path here for the tests. Requires more work.
        $urlAlias->pathData = explode( '/', trim( $row['path'], '/' ) );

        return $urlAlias;
    }
}
