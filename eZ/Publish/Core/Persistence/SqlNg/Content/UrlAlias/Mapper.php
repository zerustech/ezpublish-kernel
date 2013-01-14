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
     * Language mask generator
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator
     */
    protected $languageMaskGenerator;

    /**
     * Creates a new UrlWildcard Handler
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator $languageMaskGenerator
     */
    public function __construct( LanguageMaskGenerator $languageMaskGenerator )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a UrlAlias object from database row data
     *
     * @param mixed[] $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\UrlAlias
     */
    public function extractUrlAliasFromData( $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
