<?php
/**
 * File containing the EzcDatabase location id criterion handler class
 *
 * @copyright Copyright (C) 1999-2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Search\Gateway\CriterionHandler;

use eZ\Publish\Core\Persistence\SqlNg\Content\Location\Gateway as LocationGateway;
use eZ\Publish\Core\Persistence\SqlNg\Content\Search\Gateway\CriterionHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Search\Gateway\CriteriaConverter;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use ezcQuerySelect;

/**
 * Location id criterion handler
 */
class LocationId extends CriterionHandler
{
    /**
     * Check if this criterion handler accepts to handle the given criterion.
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion$criterion
     *
     * @return boolean
     */
    public function accept( Criterion $criterion )
    {
        return $criterion instanceof Criterion\LocationId;
    }

    /**
     * Generate query expression for a Criterion this handler accepts
     *
     * accept() must be called before calling this method.
     *
     * @param \eZ\Publish\Core\Persistence\SqlNg\Content\Search\Gateway\CriteriaConverter$converter
     * @param \ezcQuerySelect $query
     * @param \eZ\Publish\API\Repository\Values\Content\Query\Criterion$criterion
     *
     * @return \ezcQueryExpression
     */
    public function handle( CriteriaConverter $converter, ezcQuerySelect $query, Criterion $criterion )
    {
        $subSelect = $query->subSelect();
        $subSelect
            ->select(
                $this->dbHandler->quoteColumn( 'content_id' )
            )->from(
                $this->dbHandler->quoteTable( 'ezcontent_location' )
            )->where(
                $query->expr->lAnd(
                    $query->expr->in(
                        $this->dbHandler->quoteColumn( 'location_id' ),
                        $criterion->value
                    ),
                    $query->expr->eq(
                        $this->dbHandler->quoteColumn( 'status' ),
                        $query->bindValue( LocationGateway::PUBLISHED )
                    )
                )
            );

        return $query->expr->in(
            $this->dbHandler->quoteColumn( 'content_id', 'ezcontent' ),
            $subSelect
        );
    }
}

