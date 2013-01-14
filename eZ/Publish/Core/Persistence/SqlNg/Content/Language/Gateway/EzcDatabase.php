<?php
/**
 * File containing the Language Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Language\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\Content\Language\Gateway;
use eZ\Publish\SPI\Persistence\Content\Language;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use ezcQuery;

/**
 * ezcDatabase based Language Gateway
 */
class EzcDatabase extends Gateway
{
    /**
     * Database handler
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $dbHandler
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
     * Inserts the given $language
     *
     * @param Language $language
     *
     * @return int ID of the new language
     */
    public function insertLanguage( Language $language )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates the data of the given $language
     *
     * @param Language $language
     *
     * @return void
     */
    public function updateLanguage( Language $language )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads data for the Language with $id
     *
     * @param int $id
     *
     * @return string[][]
     */
    public function loadLanguageData( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads data for the Language with Language Code (eg: eng-GB)
     *
     * @param string $languageCode
     *
     * @return string[][]
     */
    public function loadLanguageDataByLanguageCode( $languageCode )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads the data for all languages
     *
     * @return string[][]
     */
    public function loadAllLanguagesData()
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes the language with $id
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteLanguage( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Check whether a language may be deleted
     *
     * @param int $id
     *
     * @return boolean
     */
    public function canDeleteLanguage( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
