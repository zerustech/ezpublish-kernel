<?php
/**
 * File containing the EzcDatabase sort clause converter class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Search\Gateway;

use eZ\Publish\API\Repository\Values\Content\Query;
use ezcQuerySelect;
use RuntimeException;

/**
 * Converter manager for sort clauses
 */
class SortClauseConverter
{
    /**
     * Sort clause handlers
     *
     * @var array(SortClauseHandler)
     */
    protected $handlers;

    /**
     * Sorting information for temporary sort columns
     *
     * @var array
     */
    protected $sortColumns = array();

    /**
     * Construct from an optional array of sort clause handlers
     *
     * @param array $handlers
     *
     * @return void
     */
    public function __construct( array $handlers = array() )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Apply select parts of sort clauses to query
     *
     * @param \ezcQuerySelect $query
     * @param array $sortClauses
     *
     * @return void
     */
    public function applySelect( ezcQuerySelect $query, array $sortClauses )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Apply join parts of sort clauses to query
     *
     * @param \ezcQuerySelect $query
     * @param array $sortClauses
     *
     * @return void
     */
    public function applyJoin( ezcQuerySelect $query, array $sortClauses )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Apply order by parts of sort clauses to query
     *
     * @param \ezcQuerySelect $query
     * @param array $sortClauses
     *
     * @return void
     */
    public function applyOrderBy( ezcQuerySelect $query, array $sortClauses )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}

