<?php
/**
 * File containing the Section Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Section;

/**
 * Section Handler
 */
abstract class Gateway
{
    /**
     * Inserts a new section with $name and $sectionIdentifier
     *
     * @param string $name
     * @param string $sectionIdentifier
     *
     * @return int The ID of the new section
     */
    abstract public function insertSection( $name, $sectionIdentifier );

    /**
     * Updates section with $sectionId to have $name and $sectionIdentifier
     *
     * @param int $sectionId
     * @param string $name
     * @param string $sectionIdentifier
     *
     * @return void
     */
    abstract public function updateSection( $sectionId, $name, $sectionIdentifier );

    /**
     * Loads data for section with $sectionId
     *
     * @param int $sectionId
     *
     * @return string[][]
     */
    abstract public function loadSectionData( $sectionId );

    /**
     * Loads data for all sections
     *
     * @return string[][]
     */
    abstract public function loadAllSectionData();

    /**
     * Loads data for section with $sectionIdentifier
     *
     * @param string $sectionIdentifier
     *
     * @return string[][]
     */
    abstract public function loadSectionDataByIdentifier( $sectionIdentifier );

    /**
     * Counts the number of content objects assigned to section with $sectionId
     *
     * @param int $sectionId
     *
     * @return int
     */
    abstract public function countContentObjectsInSection( $sectionId );

    /**
     * Deletes the Section with $sectionId
     *
     * @param int $sectionId
     *
     * @return void
     */
    abstract public function deleteSection( $sectionId );

    /**
     * Inserts the assignment of $contentId to $sectionId
     *
     * @param int $sectionId
     * @param int $contentId
     *
     * @return void
     */
    abstract public function assignSectionToContent( $sectionId, $contentId );
}
