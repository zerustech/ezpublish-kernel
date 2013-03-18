<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\HandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\Core\Persistence\SqlNg\RepositoryInitializer;

/**
 * Test case for Repository initializer
 */
class RepositoryInitializerTest extends TestCase
{
    protected function getRepositoryInitializer()
    {
        return new RepositoryInitializer(
            $this->getPersistenceHandler(),
            $this->getDatabaseHandler()
        );
    }

    public function getExpectedContent()
    {
        return array(
            array( 'f3e90596361e31d496d4026eb624c983', 'Home' ),
            array( 'admin', 'admin' ),
            array( 'anonymous', 'anonymous' ),
        );
    }

    /**
     * @dataProvider getExpectedContent
     */
    public function testGetContentFromInitializedRepo( $remoteId, $name )
    {
        $init = $this->getRepositoryInitializer();
        $init->initializeSchema();
        $init->initialize();

        $location = $this->getPersistenceHandler()->locationHandler()->loadByRemoteId( $remoteId );
        $content = $this->getPersistenceHandler()->contentHandler()->loadContentInfo( $location->contentId );

        $this->assertEquals(
            $name,
            $content->name
        );
    }

    public function getExpectedUsers()
    {
        return array(
            array( 'admin' ),
            array( 'anonymous' ),
        );
    }

    /**
     * @dataProvider getExpectedUsers
     */
    public function testGetUserFromInitializedRepo( $login )
    {
        $init = $this->getRepositoryInitializer();
        $init->initializeSchema();
        $init->initialize();

        $user = $this->getPersistenceHandler()->userHandler()->loadByLogin( $login );

        $this->assertNotNull( $user );
    }
}
