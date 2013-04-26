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
        'content' => array(
            4 => 1,
            10 => 2,
            11 => 5,
            12 => 6,
            13 => 7,
            14 => 3,
            41 => 10, // Media -> Contact Us
            42 => 8,
            54 => 10, // Demo Design -> Contact Us
            56 => 10, // Design -> Contact Us
            57 => 4,
            58 => 10,
            59 => 9,
        ),
        'group' => array(
            4 => 1, // Users
            11 => 5, // Members
            13 => 7, // Editors
        ),
        'location' => array(
            1 => 1,
            2 => 5,
            5 => 2,
            12 => 6,
            13 => 7,
            14 => 8,
            15 => 12,
            44 => 9,
            45 => 13,
            54 => 3,
            58 => 11, // Design, probably should be created
            56 => 11, // Design/PlainSite, should also be created
            60 => 11,
            61 => 10,
        ),
        'typegroup' => array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
        ),
        'type' => array(
            3 => 2, // User Group
            4 => 3, // User
            20 => 10, // Feedback Form
            22 => 11, // Wiki Page
            28 => 15, // Forum
            33 => 20, // Banner
        ),
        'user' => array(
            14 => 3, // Admin
            10 => 2, // Anonymous

            3 => 2, // Pseudo user -> anonymous
            42 => 2, // Pseudo user -> anonymous
        ),
        'section' => array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
        ),
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

        throw new \PHPUnit_Framework_IncompleteTestError(
            "Missing mapping for $type ID $rawId"
        );
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

        throw new \PHPUnit_Framework_IncompleteTestError(
            "Missing mapping for $type ID $id"
        );
    }
}
