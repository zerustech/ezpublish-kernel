<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests;

use eZ\Publish\API\Repository\Tests\SetupFactory;
use eZ\Publish\Core\Persistence\Legacy\EzcDbHandler;
use eZ\Publish\Core\Persistence\SqlNg;

/**
 * Base test case for database related tests
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $dsn;

    protected $db;

    protected $persistenceHandler;

    public function setUp()
    {
    }

    public function tearDown()
    {
        unset( $this->persistenceHandler );
    }

    /**
     * Get persistence handler
     *
     * @return Handler
     */
    protected function getPersistenceHandler()
    {
        if ( !$this->persistenceHandler )
        {
            $this->persistenceHandler = new SqlNg\Handler(
                $this->getDatabaseHandler(),
                new SqlNg\Content\StorageRegistry( array() )
            );

            $this->applyStatements(
                $this->getSchemaStatements()
            );
        }

        return $this->persistenceHandler;
    }

    /**
     * Applies the given SQL $statements to the database in use
     *
     * @param array $statements
     *
     * @return void
     */
    protected function applyStatements( array $statements )
    {
        $dbHandler = $this->getDatabaseHandler();
        $dbHandler->beginTransaction();
        foreach ( $statements as $statement )
        {
            $dbHandler->exec( $statement );
        }
        $dbHandler->commit();
    }

    /**
     * Returns the database schema as an array of SQL statements
     *
     * @return string[]
     */
    protected function getSchemaStatements()
    {

        return array_filter(
            preg_split(
                '(;\\s*$)m',
                file_get_contents(
                    __DIR__ . '/../schema/schema.' . $this->db . '.sql'
                )
            )
        );
    }

    /**
     * Get database handler
     *
     * @return EzcDbHandler
     */
    protected function getDatabaseHandler()
    {
        $this->dsn = getenv( "DATABASE" ) ?: "sqlite://:memory:";
        $this->db = preg_replace( '(^([a-z]+).*)', '\\1', $this->dsn );

        return new EzcDbHandler(
            \ezcDbFactory::create( $this->dsn )
        );
    }
}
