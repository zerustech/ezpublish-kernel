<?php
/**
 * File containing the Test Setup Factory base class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\API\Repository\Tests\SetupFactory;

use eZ\Publish\Core\Persistence\SqlNg\Handler;
use eZ\Publish\Core\Persistence\SqlNg\Content\StorageRegistry;

/**
 * A Test Factory is used to setup the infrastructure for a tests, based on a
 * specific repository implementation to test.
 */
class SqlNg extends Legacy
{
    /**
     * @var integer
     */
    protected $adminUserId = 3;

    /**
     * Creates a new setup factory
     *
     * @return void
     */
    public function __construct()
    {
        self::$dsn = getenv( "DATABASE" ) ?: 'sqlite://:memory:';
        self::$db  = preg_replace( '(^([a-z]+).*)', '\\1', self::$dsn );
    }

    /**
     * Returns a configured repository for testing.
     *
     * @param boolean $initializeFromScratch if the back end should be initialized
     *                                    from scratch or re-used
     * @return \eZ\Publish\API\Repository\Repository
     */
    public function getRepository( $initializeFromScratch = true )
    {
        if ( $initializeFromScratch )
        {
            $this->initializeSchema();
            $this->getDatabaseHandler()->query( 'SET foreign_key_checks = 0;' );
            $this->insertFixture( $this->getInitialData() );
            $this->getDatabaseHandler()->query( 'SET foreign_key_checks = 1;' );
        }

        $repository = $this->getServiceContainer()->get( 'inner_repository' );
        $persistenceHandlerProperty = new \ReflectionProperty( $repository, 'persistenceHandler' );
        $persistenceHandlerProperty->setAccessible( true );
        $persistenceHandlerProperty->setValue(
            $repository,
            $this->getServiceContainer()->get('persistence_handler_sqlng')
        );

        $repository->setCurrentUser(
            $repository->getUserService()->loadUser( $this->adminUserId )
        );
        return $repository;
    }

    /**
     * Returns a repository specific ID manager.
     *
     * @return \eZ\Publish\API\Repository\Tests\IdManager
     */
    public function getIdManager()
    {
        return new \eZ\Publish\API\Repository\Tests\IdManager\SqlNg();
    }


    /**
     * Loads the data from the fixture file
     *
     * @return array
     */
    protected function loadInitialData()
    {
        return include __DIR__ . '/../_fixtures/database/sqlng/fixture.php';
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
                    __DIR__ . '/../../../../Core/Persistence/SqlNg/schema/schema.' . self::$db . '.sql'
                )
            )
        );
    }

    /**
     * @param array $serviceSettings
     * @return array
     */
    protected function prepareServiceSettings(array $serviceSettings)
    {
        $serviceSettings['inner_repository']['arguments']['service_settings']['user']['anonymousUserID'] = 2;
        $serviceSettings['inner_repository']['arguments']['service_settings']['user']['userClassID'] = 3;
        $serviceSettings['inner_repository']['arguments']['service_settings']['user']['userGroupClassID'] = 2;

        return parent::prepareServiceSettings($serviceSettings);
    }
}
