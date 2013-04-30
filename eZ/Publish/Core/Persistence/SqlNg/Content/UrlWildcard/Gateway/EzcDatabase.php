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
use eZ\Publish\Core\Base\Exceptions\NotFoundException;

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
        $this->dbHandler = $dbHandler;
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
        /** @var $query \ezcQueryInsert */
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( "ezurl_wildcard" )
        )->set(
            $this->dbHandler->quoteColumn( "wildcard_id" ),
            $this->dbHandler->getAutoIncrementValue( "ezurl_wildcard", "wildcard_id" )
        )->set(
            $this->dbHandler->quoteColumn( "destination" ),
            $query->bindValue( $urlWildcard->destinationUrl, null, \PDO::PARAM_STR )
        )->set(
            $this->dbHandler->quoteColumn( "source" ),
            $query->bindValue( $urlWildcard->sourceUrl, null, \PDO::PARAM_STR )
        )->set(
            $this->dbHandler->quoteColumn( "type" ),
            $query->bindValue( $urlWildcard->forward, null, \PDO::PARAM_INT )
        );

        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( "ezurl_wildcard", "wildcard_id" )
        );
    }

    /**
     * Deletes the UrlWildcard with given $wildcardId
     *
     * @param mixed $wildcardId
     *
     * @return vowildcard_id
     */
    public function deleteUrlWildcard( $wildcardId )
    {
        /** @var $query \ezcQueryDelete */
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom(
            $this->dbHandler->quoteTable( "ezurl_wildcard" )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( "wildcard_id" ),
                $query->bindValue( $wildcardId, null, \PDO::PARAM_INT )
            )
        );
        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFoundException( 'UrlWildcard', $wildcardId );
        }
    }

    /**
     * Loads an array with data about UrlWildcard with $wildcardId
     *
     * @param mixed $wildcardId
     *
     * @return array
     */
    public function loadUrlWildcardData( $wildcardId )
    {
        /** @var $query \ezcQuerySelect */
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( "wildcard_id" ),
            $this->dbHandler->quoteColumn( "destination" ),
            $this->dbHandler->quoteColumn( "source" ),
            $this->dbHandler->quoteColumn( "type" )
        )->from(
            $this->dbHandler->quoteTable( "ezurl_wildcard" )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( "wildcard_id" ),
                $query->bindValue( $wildcardId, null, \PDO::PARAM_INT )
            )
        );
        $stmt = $query->prepare();
        $stmt->execute();

        return $stmt->fetch( \PDO::FETCH_ASSOC );
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
        $limit = $limit === -1 ? self::MAX_LIMIT : $limit;

        /** @var $query \ezcQuerySelect */
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( "wildcard_id" ),
            $this->dbHandler->quoteColumn( "destination" ),
            $this->dbHandler->quoteColumn( "source" ),
            $this->dbHandler->quoteColumn( "type" )
        )->from(
            $this->dbHandler->quoteTable( "ezurl_wildcard" )
        )->limit( $limit, $offset );

        $stmt = $query->prepare();
        $stmt->execute();

        return $stmt->fetchAll( \PDO::FETCH_ASSOC );
    }
}
