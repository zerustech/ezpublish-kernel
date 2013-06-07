<?php
/**
 * File containing the UrlAlias ezcDatabase Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator as LanguageMaskGenerator;
use eZ\Publish\SPI\Persistence\Content\UrlAlias;
use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;
use ezcQuery;

/**
 * UrlAlias Gateway
 */
class EzcDatabase extends Gateway
{
    /**
     * 2^30, since PHP_INT_MAX can cause overflows in DB systems, if PHP is run
     * on 64 bit systems
     */
    const MAX_LIMIT = 1073741824;

    /**
     * Zeta Components database handler.
     *
     * @var \ezcDbHandler
     */
    protected $dbHandler;

    /**
     * Creates a new EzcDatabase UrlAlias Gateway
     *
     * @param \eZ\Publish\Core\Persistence\Legacy\EzcDbHandler $dbHandler
     */
    public function __construct ( EzcDbHandler $dbHandler )
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     * Create custom URL alias
     *
     * @param int $type
     * @param mixed $destination
     * @param bool $isforward
     * @param bool $isHistory
     * @param bool $isCustom
     * @return void
     */
    public function createUrlAlias( $type, $destination, $isforward, $isHistory, $isCustom )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezurl_alias' )
        )->set(
            $this->dbHandler->quoteColumn( 'alias_id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezurl_alias', 'alias_id' )
        )->set(
            $this->dbHandler->quoteColumn( 'type' ),
            $query->bindValue( $type, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'destination' ),
            $query->bindValue( $destination, null, \PDO::PARAM_STR )
        )->set(
            $this->dbHandler->quoteColumn( 'forward' ),
            $query->bindValue( $isforward, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'history' ),
            $query->bindValue( $isHistory, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'custom' ),
            $query->bindValue( $isCustom, null, \PDO::PARAM_INT )
        );

        if ( $type === UrlAlias::LOCATION )
        {
            $query->set(
                $this->dbHandler->quoteColumn( 'location_id' ),
                $query->bindValue( $destination, null, \PDO::PARAM_INT )
            );
        }

        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezurl_alias', 'alias_id' )
        );
    }

    /**
     * update old aliases for location
     *
     * @param int $locationId
     * @return void
     */
    public function updateOldAliasesForLocation( $locationId )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezurl_alias' )
        )->set(
            $this->dbHandler->quoteColumn( 'forward' ),
            $query->bindValue( true, null, \PDO::PARAM_BOOL )
        )->set(
            $this->dbHandler->quoteColumn( 'history' ),
            $query->bindValue( true, null, \PDO::PARAM_BOOL )
        )->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "location_id", "ezurl_alias" ),
                    $query->bindValue( $locationId, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "history", "ezurl_alias" ),
                    $query->bindValue( false, null, \PDO::PARAM_BOOL )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "custom", "ezurl_alias" ),
                    $query->bindValue( false, null, \PDO::PARAM_BOOL )
                )
            )
        );

        $query->prepare()->execute();
    }

    /**
     * Add translated URL
     *
     * @param int $aliasId
     * @param string $path
     * @param string $pathHash
     * @param int $languageId
     * @return void
     */
    public function addTranslatedPath( $aliasId, $path, $pathHash, $languageId )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezurl_alias_language' )
        )->set(
            $this->dbHandler->quoteColumn( 'alias_id' ),
            $query->bindValue( $aliasId, null, \PDO::PARAM_INT )
        )->set(
            $this->dbHandler->quoteColumn( 'path' ),
            $query->bindValue( $path, null, \PDO::PARAM_STR )
        )->set(
            $this->dbHandler->quoteColumn( 'path_hash' ),
            $query->bindValue( $pathHash, null, \PDO::PARAM_STR )
        )->set(
            $this->dbHandler->quoteColumn( 'language_id' ),
            $query->bindValue( $languageId, null, \PDO::PARAM_INT )
        );

        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezurl_alias', 'alias_id' )
        );
    }

    /**
     * Load URL Alias
     *
     * @param int $id
     * @return array
     */
    public function load( $aliasId )
    {
        $query = $this->getLoadQuery();
        $query->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( "alias_id", "ezurl_alias" ),
                $query->bindValue( $aliasId, null, \PDO::PARAM_INT )
            )
        );
        $statement = $query->prepare();
        $statement->execute();
        $rows = $statement->fetchAll( \PDO::FETCH_ASSOC );

        if ( !count( $rows ) )
        {
            throw new NotFound( "UrlAlias", $aliasId );
        }

        return $rows;
    }

    /**
     * Load URL Aliases for location
     *
     * @param int $locationId
     * @param bool $custom
     * @return array
     */
    public function loadForLocation( $locationId, $custom, $history = null )
    {
        $query = $this->getLoadQuery();
        $query->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "location_id", "ezurl_alias" ),
                    $query->bindValue( $locationId, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "custom", "ezurl_alias" ),
                    $query->bindValue( $custom, null, \PDO::PARAM_BOOL )
                )
            )
        );

        if ( $history !== null )
        {
            $query->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "history", "ezurl_alias" ),
                    $query->bindValue( $history, null, \PDO::PARAM_BOOL )
                )
            );
        }

        $statement = $query->prepare();
        $statement->execute();
        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Load URL Aliases for location
     *
     * @param string $languageCode
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function loadGlobalUrlAliases( $languageCode, $offset, $limit )
    {
        $query = $this->getLoadQuery();
        $query->where(
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "type", 'ezurl_alias' ),
                    $query->bindValue( UrlAlias::RESOURCE, null, \PDO::PARAM_INT )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( "custom", "ezurl_alias" ),
                    $query->bindValue( true, null, \PDO::PARAM_BOOL )
                )
            )
        );

        if ( $languageCode )
        {
            $query->where(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'language_code', 'ezcontent_language' ),
                    $query->bindValue( $languageCode, null, \PDO::PARAM_STR )
                )
            );
        }

        $limit = $limit !== null ? $limit : self::MAX_LIMIT;
        $query->limit( $limit, $offset );

        $statement = $query->prepare();
        $statement->execute();
        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Remove vustom URL aliases
     *
     * @param int[] $aliasIds
     * @return array
     */
    public function removeAliases( $aliasIds )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom( 'ezurl_alias' )
            ->where(
                $query->expr->lAnd(
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'custom' ),
                        $query->bindValue( true, null, \PDO::PARAM_BOOL )
                    ),
                    $query->expr->in(
                        $this->dbHandler->quoteColumn( 'alias_id' ),
                        $aliasIds
                    )
                )
            );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < count( $aliasIds ) )
        {
            throw new NotFound( 'UrlAlias', implode( ", ", $aliasIds ) );
        }
    }

    /**
     * Get URL Alias load query
     *
     * @return ezcQuerySelect
     */
    protected function getLoadQuery()
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'alias_id', 'ezurl_alias' ),
            $this->dbHandler->quoteColumn( 'type', 'ezurl_alias' ),
            $this->dbHandler->quoteColumn( 'destination', 'ezurl_alias' ),
            $this->dbHandler->quoteColumn( 'forward', 'ezurl_alias' ),
            $this->dbHandler->quoteColumn( 'history', 'ezurl_alias' ),
            $this->dbHandler->quoteColumn( 'custom', 'ezurl_alias' ),
            $this->dbHandler->quoteColumn( 'path', 'ezurl_alias_language' ),
            $this->dbHandler->quoteColumn( 'language_code', 'ezcontent_language' )
        )->from(
            $this->dbHandler->quoteTable( "ezurl_alias" )
        )->leftJoin(
            $this->dbHandler->quoteTable( "ezurl_alias_language" ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( "alias_id", "ezurl_alias" ),
                $this->dbHandler->quoteColumn( "alias_id", "ezurl_alias_language" )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( "ezcontent_language" ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( "language_id", "ezurl_alias_language" ),
                $this->dbHandler->quoteColumn( "language_id", "ezcontent_language" )
            )
        );

        return $query;
    }
    
}
