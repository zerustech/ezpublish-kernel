<?php
/**
 * File containing the Section Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Section\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\Content\Section\Gateway;

/**
 * Section Handler
 */
class ExceptionConversion extends Gateway
{
    /**
     * The wrapped gateway
     *
     * @var Gateway
     */
    protected $innerGateway;

    /**
     * Creates a new exception conversion gateway around $innerGateway
     *
     * @param Gateway $innerGateway
     */
    public function __construct( Gateway $innerGateway )
    {
        $this->innerGateway = $innerGateway;
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
        try
        {
            return $this->innerGateway->insertSection( $name, $sectionIdentifier );
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
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
        try
        {
            return $this->innerGateway->updateSection( $sectionId, $name, $sectionIdentifier );
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
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
        try
        {
            return $this->innerGateway->loadSectionData( $sectionId );
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
    }

    /**
     * Loads data for all sections
     *
     * @return string[][]
     */
    public function loadAllSectionData()
    {
        try
        {
            return $this->innerGateway->loadAllSectionData();
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
    }

    /**
     * Loads data for section with $sectionIdentifier
     *
     * @param string $sectionIdentifier
     *
     * @return string[][]
     */
    public function loadSectionDataByIdentifier( $sectionIdentifier )
    {
        try
        {
            return $this->innerGateway->loadSectionDataByIdentifier( $sectionIdentifier );
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
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
        try
        {
            return $this->innerGateway->countContentObjectsInSection( $sectionId );
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
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
        try
        {
            return $this->innerGateway->deleteSection( $sectionId );
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
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
        try
        {
            return $this->innerGateway->assignSectionToContent( $sectionId, $contentId );
        }
        catch ( \ezcDbException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
        catch ( \PDOException $e )
        {
            throw new \RuntimeException( 'Database error', 0, $e );
        }
    }
}
