<?php
/**
 * File containing the Location Handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Location;

use eZ\Publish\SPI\Persistence\Content\Location;
use eZ\Publish\SPI\Persistence\Content\Location\CreateStruct;

/**
 * Mapper for Location objects
 */
class Mapper
{
    /**
     * Creates a Location from a $data row
     *
     * $prefix can be used to define a table prefix for the location table.
     *
     * Optionally pass a Location object, which will be filled with the values.
     *
     * @param array $data
     * @param string $prefix
     * @param Location $location
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location
     */
    public function createLocationFromRow( array $data, $prefix = '', Location $location = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates Location objects from the given $rows, optionally with key
     * $prefix
     *
     * @param array $rows
     * @param string $prefix
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location[]
     */
    public function createLocationsFromRows( array $rows, $prefix = '' )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a Location CreateStruct from a $data row
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Location\CreateStruct
     */
    public function getLocationCreateStruct( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
