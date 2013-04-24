<?php
/**
 * File containing the IdManager class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests\IdManager;

use eZ\Publish\API\Repository\Tests\IdManager;

/**
 * ID manager that provides a mapping between legacy identifiers and sqlng
 * identifiers.
 */
class SqlNg extends IdManager
{
    private $mapping = array(
        'object' => array(
            11 => 5, // Members
            41 => 10, // Media -> Contact Us
            58 => 10, // Partner -> Contact Us
        ),
        'content' => array(
            11 => 5, // Members
            41 => 10, // Media -> Contact Us
            58 => 10, // Partner -> Contact Us
        ),
        'group' => array(
            4 => 1, // Users
            13 => 7, // Editors
        ),
        'location' => array(
            5 => 2, // Users
            56 => 11, // design/plain_site -> contact us
            60 => 11, // ??? -> contact us
        ),
        'typegroup' => array(
            2 => 2, // Users
        ),
        'type' => array(
            3 => 2, // User Group
            4 => 3, // User
            20 => 10, // Feedback Form
            22 => 11, // Wiki Page
            28 => 15, // Forum
            33 => 20, // Banner
        )
    );

    /**
     * Generates a repository specific ID.
     *
     * Generates a repository specific ID for an object of $type from the
     * database ID $rawId.
     *
     * @param string $type
     * @param mixed $rawId
     *
     * @return mixed
     */
    public function generateId( $type, $rawId )
    {
        if ( isset( $this->mapping[$type][$rawId] ) )
        {
            return $this->mapping[$type][$rawId];
        }
        // TODO Throw an exception?
        return $rawId;
    }

    /**
     * Parses the given $id for $type into its raw form.
     *
     * Takes a repository specific $id of $type and returns the raw database ID
     * for the object.
     *
     * @param string $type
     * @param mixed $id
     *
     * @return mixed
     */
    public function parseId( $type, $id )
    {
        if ( isset( $this->mapping[$type] ) && is_int( $rawId = array_search( $id, $this->mapping[$type] ) ) )
        {
            return $rawId;
        }
        // TODO Throw an exception?
        return $id;
    }
}
