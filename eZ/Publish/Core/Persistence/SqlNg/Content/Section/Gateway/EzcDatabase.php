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
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Inserts a new section with $name and $identifier
     *
     * @param string $name
     * @param string $identifier
     *
     * @return int The ID of the new section
     */
    public function insertSection( $name, $identifier )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Updates section with $id to have $name and $identifier
     *
     * @param int $id
     * @param string $name
     * @param string $identifier
     *
     * @return void
     */
    public function updateSection( $id, $name, $identifier )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads data for section with $id
     *
     * @param int $id
     *
     * @return string[][]
     */
    public function loadSectionData( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads data for all sections
     *
     * @return string[][]
     */
    public function loadAllSectionData()
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Loads data for section with $identifier
     *
     * @param int $identifier
     *
     * @return string[][]
     */
    public function loadSectionDataByIdentifier( $identifier )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Counts the number of content objects assigned to section with $id
     *
     * @param int $id
     *
     * @return int
     */
    public function countContentObjectsInSection( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Deletes the Section with $id
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteSection( $id )
    {
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
