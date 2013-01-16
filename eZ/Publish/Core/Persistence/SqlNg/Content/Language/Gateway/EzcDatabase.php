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
use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;
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
        $this->dbHandler = $dbHandler;
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
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezcontent_language' )
        )->set(
            $this->dbHandler->quoteColumn( 'id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezcontent_language', 'id' )
        )->set(
            $this->dbHandler->quoteColumn( 'language_code' ),
            $query->bindValue( $language->languageCode )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( $language->name )
        )->set(
            $this->dbHandler->quoteColumn( 'is_enabled' ),
            $query->bindValue( $language->isEnabled, null, \PDO::PARAM_INT )
        );
        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezcontent_language', 'id' )
        );
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
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent_language' )
        )->set(
            $this->dbHandler->quoteColumn( 'language_code' ),
            $query->bindValue( $language->languageCode )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( $language->name )
        )->set(
            $this->dbHandler->quoteColumn( 'is_enabled' ),
            $query->bindValue( $language->isEnabled, null, \PDO::PARAM_INT )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'id' ),
                $query->bindValue( $language->id, null, \PDO::PARAM_INT )
            )
        );

        $query->prepare()->execute();
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
        $query = $this->createFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'id' ),
                $query->bindValue( $id, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
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
        $query = $this->createFindQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'language_code' ),
                $query->bindValue( $languageCode, null, \PDO::PARAM_STR )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads the data for all languages
     *
     * @return string[][]
     */
    public function loadAllLanguagesData()
    {
        $query = $this->createFindQuery();

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Creates a Language find query
     *
     * @return \ezcQuerySelect
     */
    protected function createFindQuery()
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'id' ),
            $this->dbHandler->quoteColumn( 'language_code' ),
            $this->dbHandler->quoteColumn( 'name' ),
            $this->dbHandler->quoteColumn( 'is_enabled' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_language' )
        );

        return $query;
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
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom(
            $this->dbHandler->quoteTable( 'ezcontent_language' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'id' ),
                $query->bindValue( $id, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'language', $id );
        }
    }
}
