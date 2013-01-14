<?php
/**
 * File containing the Language Handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Language;

use eZ\Publish\SPI\Persistence\Content\Language;
use eZ\Publish\SPI\Persistence\Content\Language\Handler as BaseLanguageHandler;
use eZ\Publish\SPI\Persistence\Content\Language\CreateStruct;
use eZ\Publish\Core\Base\Exceptions\NotFoundException;
use LogicException;

/**
 * Language Handler
 */
class Handler implements BaseLanguageHandler
{
    /**
     * Language Gateway
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Gateway
     */
    protected $languageGateway;

    /**
     * Language Mapper
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Mapper
     */
    protected $languageMapper;

    /**
     * Creates a new Language Handler
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Gateway $languageGateway
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Language\Mapper $languageMapper
     */
    public function __construct( Gateway $languageGateway, Mapper $languageMapper )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Create a new language
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Language\CreateStruct $struct
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language
     */
    public function create( CreateStruct $struct )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Update language
     *
     * @param \eZ\Publish\SPI\Persistence\Content\Language $language
     */
    public function update( Language $language )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Get language by id
     *
     * @param mixed $id
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If language could not be found by $id
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language
     */
    public function load( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Get language by Language Code (eg: eng-GB)
     *
     * @param string $languageCode
     *
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException If language could not be found by $languageCode
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language
     */
    public function loadByLanguageCode( $languageCode )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Get all languages
     *
     * @return \eZ\Publish\SPI\Persistence\Content\Language[]
     */
    public function loadAll()
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Delete a language
     *
     * @param mixed $id
     *
     * @throws LogicException If language could not be deleted
     */
    public function delete( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
