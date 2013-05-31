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
            13 => 7, // Editors
            41 => 10, // Media -> Contact Us
            58 => 10, // Partner -> Contact Us
        ),
        'content' => array(
            4 => 1, // Users -> Users
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
            2 => 5,   // => content: 57
            5 => 2,   // => content: 4
            12 => 6,  // => content: 11
            13 => 7,  // => content: 12
            14 => 8,  // => content: 13
            15 => 12, // => content: 14
            44 => 9,  // => content: 42
            45 => 13, // => content: 10
            54 => 3,  // => content: 52
            58 => 11, // => content: 56; Design, probably should be created
            56 => 11, // => content: 54; Design/PlainSite, should also be created
            60 => 11, // => content: 58
            61 => 10, // => content: 59
        ),
        'typegroup' => array(
            1 => 1,
            2 => 2, // Users
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
        'remoteId' => array(
            'a6e35cbcb7cd6ae4b691f3eee30cd262' => 'f5c88a2209584891056f987fd965b0ba', // Media -> Users
            'f3e90596361e31d496d4026eb624c983' => 'f3e90596361e31d496d4026eb624c983', // Home -> Home
            '3f6d92f8044aed134f32153517850f5a' => 'f5c88a2209584891056f987fd965b0ba', // Users -> Users
        ),
        'content_remote_id' => array(
            'f8cc7a4cf8a964a1a0ea6666f5da7d0d' => 'f8cc7a4cf8a964a1a0ea6666f5da7d0d', // Contact Us -> Contact Us
        ),
        'location_remote_id' => array(
            '4fdf0072da953bb276c0c7e0141c5c9b' => '14e4411b264a6194a33847843919451a', // 44 -> 9
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
