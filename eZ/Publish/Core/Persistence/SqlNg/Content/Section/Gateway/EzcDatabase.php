<?php
/**
 * File containing the Section ezcDatabase Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Section\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\Content\Section\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;

use eZ\Publish\Core\Base\Exceptions\NotFoundException as NotFound;

/**
 * Section Handler
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
     * Inserts a new section with $name and $sectionIdentifier
     *
     * @param string $name
     * @param string $sectionIdentifier
     *
     * @return int The ID of the new section
     */
    public function insertSection( $name, $sectionIdentifier )
    {
        $query = $this->dbHandler->createInsertQuery();
        $query->insertInto(
            $this->dbHandler->quoteTable( 'ezsection' )
        )->set(
            $this->dbHandler->quoteColumn( 'section_id' ),
            $this->dbHandler->getAutoIncrementValue( 'ezsection', 'section_id' )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( $name )
        )->set(
            $this->dbHandler->quoteColumn( 'identifier' ),
            $query->bindValue( $sectionIdentifier )
        );

        $query->prepare()->execute();

        return $this->dbHandler->lastInsertId(
            $this->dbHandler->getSequenceName( 'ezsection', 'section_id' )
        );
    }

    /**
     * Updates section with $sectionId to have $name and $sectionIdentifier
     *
     * @param int $sectionId
     * @param string $name
     * @param string $sectionIdentifier
     *
     * @return void
     */
    public function updateSection( $sectionId, $name, $sectionIdentifier )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezsection' )
        )->set(
            $this->dbHandler->quoteColumn( 'name' ),
            $query->bindValue( $name )
        )->set(
            $this->dbHandler->quoteColumn( 'identifier' ),
            $query->bindValue( $sectionIdentifier )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'section_id' ),
                $query->bindValue( $sectionId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'section', $sectionId );
        }
    }

    /**
     * Loads data for section with $sectionId
     *
     * @param int $sectionId
     *
     * @return string[][]
     */
    public function loadSectionData( $sectionId )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'section_id' ),
            $this->dbHandler->quoteColumn( 'identifier' ),
            $this->dbHandler->quoteColumn( 'name' )
        )->from(
            $this->dbHandler->quoteTable( 'ezsection' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'section_id' ),
                $query->bindValue( $sectionId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for all sections
     *
     * @return string[][]
     */
    public function loadAllSectionData()
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'section_id' ),
            $this->dbHandler->quoteColumn( 'identifier' ),
            $this->dbHandler->quoteColumn( 'name' )
        )->from(
            $this->dbHandler->quoteTable( 'ezsection' )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Loads data for section with $sectionIdentifier
     *
     * @param int $sectionIdentifier
     *
     * @return string[][]
     */
    public function loadSectionDataByIdentifier( $sectionIdentifier )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $this->dbHandler->quoteColumn( 'section_id' ),
            $this->dbHandler->quoteColumn( 'identifier' ),
            $this->dbHandler->quoteColumn( 'name' )
        )->from(
            $this->dbHandler->quoteTable( 'ezsection' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'identifier' ),
                $query->bindValue( $sectionIdentifier, null, \PDO::PARAM_STR )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return $statement->fetchAll( \PDO::FETCH_ASSOC );
    }

    /**
     * Counts the number of content objects assigned to section with $sectionId
     *
     * @param int $sectionId
     *
     * @return int
     */
    public function countContentObjectsInSection( $sectionId )
    {
        $query = $this->dbHandler->createSelectQuery();
        $query->select(
            $query->alias(
                $query->expr->count(
                    $this->dbHandler->quoteColumn( 'content_id' )
                ),
                'content_count'
            )
        )->from(
            $this->dbHandler->quoteTable( 'ezcontent' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'section_id' ),
                $query->bindValue( $sectionId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        return (int)$statement->fetchColumn();
    }

    /**
     * Deletes the Section with $sectionId
     *
     * @param int $sectionId
     *
     * @return void
     */
    public function deleteSection( $sectionId )
    {
        $query = $this->dbHandler->createDeleteQuery();
        $query->deleteFrom(
            $this->dbHandler->quoteTable( 'ezsection' )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'section_id' ),
                $query->bindValue( $sectionId, null, \PDO::PARAM_INT )
            )
        );

        $statement = $query->prepare();
        $statement->execute();

        if ( $statement->rowCount() < 1 )
        {
            throw new NotFound( 'section', $sectionId );
        }
    }

    /**
     * Inserts the assignment of $contentId to $sectionId
     *
     * @param int $sectionId
     * @param int $contentId
     *
     * @return void
     */
    public function assignSectionToContent( $sectionId, $contentId )
    {
        $query = $this->dbHandler->createUpdateQuery();
        $query->update(
            $this->dbHandler->quoteTable( 'ezcontent' )
        )->set(
            $this->dbHandler->quoteColumn( 'section_id' ),
            $query->bindValue( $sectionId, null, \PDO::PARAM_INT )
        )->where(
            $query->expr->eq(
                $this->dbHandler->quoteColumn( 'section_id' ),
                $query->bindValue( $contentId, null, \PDO::PARAM_INT )
            )
        );

        $query->prepare()->execute();
    }
}
