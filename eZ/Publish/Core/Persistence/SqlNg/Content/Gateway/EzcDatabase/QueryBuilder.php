<?php
/**
 * File containing the EzcDatabase query builder class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Gateway\EzcDatabase;

use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;

class QueryBuilder
{
    /**
     * Database handler
     *
     * @var \EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Creates a new query builder.
     *
     * @param \EzcDbHandler $dbHandler
     */
    public function __construct( ezcDbHandler $dbHandler )
    {
        $this->dbHandler = $dbHandler;
    }

    /**
     * Creates a select query for content objects
     *
     * Creates a select query with all necessary joins to fetch a complete
     * content object. Does not apply any WHERE conditions.
     *
     * @param string[] $translations
     *
     * @return \ezcQuerySelect
     */
    public function createFindQuery( array $translations = null )
    {
        /** @var $query \ezcQuerySelect */
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            // Content object
            $this->dbHandler->aliasedColumn( $query, 'id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'contenttype_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'current_version_no', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'initial_language_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'always_available', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'name_list', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'owner_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'modified', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'published', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'remote_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'section_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'status', 'ezcontent' ),
            // Content object version
            $this->dbHandler->aliasedColumn( $query, 'id', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'version_no', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'modified', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'creator_id', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'created', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'initial_language_id', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'status', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'fields', 'ezcontent_version' ),
            // Content object locations
            $this->dbHandler->aliasedColumn( $query, 'main_id', 'ezcontent_location' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent' )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezcontent_version' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_version' ),
                $this->dbHandler->quoteColumn( 'id', 'ezcontent' )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezcontent_location' ),
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_location' ),
                    $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_version' )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_version_no', 'ezcontent_location' ),
                    $this->dbHandler->quoteColumn( 'version_no', 'ezcontent_version' )
                )
            )
        );

        return $query;
    }

    /**
     * Creates a select query for content relations
     *
     * @return \ezcQuerySelect
     */
    public function createRelationFindQuery()
    {
        /** @var $query \ezcQuerySelect */
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->aliasedColumn( $query, 'id', 'ezcontent_relation' ),
            $this->dbHandler->aliasedColumn( $query, 'contenttypeattribute_id', 'ezcontent_relation' ),
            $this->dbHandler->aliasedColumn( $query, 'from_content_id', 'ezcontent_relation' ),
            $this->dbHandler->aliasedColumn( $query, 'from_content_version', 'ezcontent_relation' ),
            $this->dbHandler->aliasedColumn( $query, 'op_code', 'ezcontent_relation' ),
            $this->dbHandler->aliasedColumn( $query, 'relation_type', 'ezcontent_relation' ),
            $this->dbHandler->aliasedColumn( $query, 'to_content_id', 'ezcontent_relation' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_relation' )
        );

        return $query;
    }

    /**
     * Creates a select query for content version objects
     *
     * Creates a select query with all necessary joins to fetch a complete
     * content object. Does not apply any WHERE conditions.
     *
     * @return \ezcQuerySelect
     */
    public function createVersionInfoFindQuery()
    {
        /** @var $query \ezcQuerySelect */
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            // Content object
            $this->dbHandler->aliasedColumn( $query, 'id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'contenttype_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'current_version_no', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'initial_language_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'always_available', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'name_list', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'owner_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'modified', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'published', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'remote_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'section_id', 'ezcontent' ),
            $this->dbHandler->aliasedColumn( $query, 'status', 'ezcontent' ),
            // Content object version
            $this->dbHandler->aliasedColumn( $query, 'id', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'version_no', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'modified', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'creator_id', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'created', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'initial_language_id', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'status', 'ezcontent_version' ),
            $this->dbHandler->aliasedColumn( $query, 'fields', 'ezcontent_version' ),
            // Content object locations
            $this->dbHandler->aliasedColumn( $query, 'main_id', 'ezcontent_location' )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent_version' )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezcontent' ),
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'id', 'ezcontent' ),
                $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_version' )
            )
        )->leftJoin(
            $this->dbHandler->quoteTable( 'ezcontent_location' ),
            $query->expr->lAnd(
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_location' ),
                    $this->dbHandler->quoteColumn( 'content_id', 'ezcontent_version' )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'content_version_no', 'ezcontent_location' ),
                    $this->dbHandler->quoteColumn( 'version_no', 'ezcontent_version' )
                ),
                $query->expr->eq(
                    $this->dbHandler->quoteColumn( 'main_id', 'ezcontent_location' ),
                    $this->dbHandler->quoteColumn( 'id', 'ezcontent_location' )
                )
            )
        );

        return $query;
    }
}
