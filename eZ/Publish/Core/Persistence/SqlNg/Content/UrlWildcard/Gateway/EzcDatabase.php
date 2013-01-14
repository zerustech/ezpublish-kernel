<?php
/**
 * File containing the UrlWildcard ezcDatabase Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\UrlWildcard\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\Content\UrlWildcard\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\SPI\Persistence\Content\UrlWildcard;

/**
 * UrlWildcard Gateway
 */
class EzcDatabase extends Gateway
{
    /**
     * 2^30, since PHP_INT_MAX can cause overflows in DB systems, if PHP is run
     * on 64 bit systems
     */
    const MAX_LIMIT = 1073741824;

    /**
     * Database handler
     *
     * @var \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Creates a new EzcDatabase Section Gateway
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $dbHandler
     */
    public function __construct ( EzcDbHandler $dbHandler )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Inserts the given UrlWildcard
     *
     * @param \eZ\Publish\SPI\Persistence\Content\UrlWildcard $urlWildcard
     *
     * @return mixed
     */
    public function insertUrlWildcard( UrlWildcard $urlWildcard )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes the UrlWildcard with given $id
     *
     * @param mixed $id
     *
     * @return void
     */
    public function deleteUrlWildcard( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads an array with data about UrlWildcard with $id
     *
     * @param mixed $id
     *
     * @return array
     */
    public function loadUrlWildcardData( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads an array with data about UrlWildcards (paged)
     *
     * @param mixed $offset
     * @param mixed $limit
     *
     * @return array
     */
    public function loadUrlWildcardsData( $offset = 0, $limit = -1 )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
