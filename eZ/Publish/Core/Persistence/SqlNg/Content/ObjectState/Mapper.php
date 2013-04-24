<?php
/**
 * File containing the ObjectState Mapper class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\ObjectState;

use eZ\Publish\SPI\Persistence\Content\ObjectState;
use eZ\Publish\SPI\Persistence\Content\ObjectState\Group;
use eZ\Publish\SPI\Persistence\Content\ObjectState\InputStruct;
use eZ\Publish\SPI\Persistence\Content\Language\Handler as LanguageHandler;

/**
 * Mapper for ObjectState and object state Group objects
 */
class Mapper
{
    /**
     * Creates ObjectState object from provided $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState
     */
    public function createObjectStateFromData( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates ObjectState array of objects from provided $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState[]
     */
    public function createObjectStateListFromData( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates ObjectStateGroup object from provided $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState\Group
     */
    public function createObjectStateGroupFromData( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates ObjectStateGroup array of objects from provided $data
     *
     * @param array $data
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState\Group[]
     */
    public function createObjectStateGroupListFromData( array $data )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates an instance of ObjectStateGroup object from provided $input struct
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\InputStruct $input
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState\Group
     */
    public function createObjectStateGroupFromInputStruct( InputStruct $input )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates an instance of ObjectState object from provided $input struct
     *
     * @param \eZ\Publish\SPI\Persistence\Content\ObjectState\InputStruct $input
     *
     * @return \eZ\Publish\SPI\Persistence\Content\ObjectState
     */
    public function createObjectStateFromInputStruct( InputStruct $input )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
