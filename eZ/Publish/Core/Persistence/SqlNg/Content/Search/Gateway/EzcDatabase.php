<?php
/**
 * File containing the EzcDatabase content locator gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\Search\Gateway;

use eZ\Publish\Core\Persistence\SqlNg\Content\Search\Gateway;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\Core\Persistence\SqlNg\Content\Gateway\EzcDatabase\QueryBuilder;
use eZ\Publish\Core\Persistence\SqlNg\Content\Language\MaskGenerator as LanguageMaskGenerator;
use eZ\Publish\SPI\Persistence\Content\Language\Handler as LanguageHandler;
use eZ\Publish\API\Repository\Values\Content\Query\Criterion;
use eZ\Publish\API\Repository\Values\Content\VersionInfo;
use ezcQuerySelect;

/**
 * Content locator gateway implementation using the zeta dbHandler component.
 */
class EzcDatabase extends Gateway
{
    /**
     * 2^30, since PHP_INT_MAX can cause overflows in DB systems, if PHP is run
     * on 64 bit systems
     */
    const MAX_LIMIT = 1073741824;

    /**
     * Database dbHandler
     *
     * @var EzcDbHandler
     */
    protected $dbHandler;

    /**
     * Criteria converter
     *
     * @var CriteriaConverter
     */
    protected $criteriaConverter;

    /**
     * Sort clause converter
     *
     * @var SortClauseConverter
     */
    protected $sortClauseConverter;

    /**
     * Content load query builder
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Gateway\EzcDatabase\QueryBuilder
     */
    protected $queryBuilder;

    /**
     * Caching language dbHandler
     *
     * @var \eZ\Publish\Core\Persistence\SqlNg\Content\Language\CachingHandler
     */
    protected $languageHandler;

    /**
     * Construct from dbHandler dbHandler
     *
     * @param \EzcDbHandler $dbHandler
     *
     * @return void
     */
    public function __construct(
        EzcDbHandler $dbHandler,
        CriteriaConverter $criteriaConverter,
        SortClauseConverter $sortClauseConverter,
        QueryBuilder $queryBuilder,
        LanguageHandler $languageHandler
    )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }

    /**
     * Returns a list of object satisfying the $criterion.
     *
     * @todo Check Query recreation in this method. Something breaks if we reuse
     *       the query, after we have added the applyJoin() stuff here.
     *
     * @param Criterion $criterion
     * @param int $offset
     * @param int|null $limit
     * @param \eZ\Publish\API\Repository\Values\Content\Query\SortClause[] $sort
     * @param string[] $translations
     *
     * @return mixed[][]
     */
    public function find( Criterion $criterion, $offset = 0, $limit = null, array $sort = null, array $translations = null )
    {
        throw new \RuntimeException( "@TODO: Implement" );
    }
}

