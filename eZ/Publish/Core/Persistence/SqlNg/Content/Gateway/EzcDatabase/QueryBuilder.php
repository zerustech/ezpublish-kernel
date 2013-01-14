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
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Creates a select query for content relations
     *
     * @return \ezcQuerySelect
     */
    public function createRelationFindQuery()
    {
        throw new \RuntimeException( "@TODO: Implement" );
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
        throw new \RuntimeException( "@TODO: Implement" );
    }
}
