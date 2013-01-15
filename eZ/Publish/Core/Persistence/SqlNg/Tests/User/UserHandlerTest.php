<?php
/**
 * File contains: eZ\Publish\Core\Persistence\SqlNg\Tests\User\UserHandlerTest class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Tests\User;

use eZ\Publish\Core\Persistence\SqlNg\Tests\TestCase;
use eZ\Publish\Core\Persistence\SqlNg\User;
use eZ\Publish\SPI\Persistence;

/**
 * Test case for UserHandlerTest
 */
class UserHandlerTest extends TestCase
{
    /**
     * Returns the test suite with all tests declared in this class.
     *
     * @return \PHPUnit_Framework_TestSuite
     */
    public static function suite()
    {
        return new \PHPUnit_Framework_TestSuite( __CLASS__ );
    }

    protected function getUserHandler()
    {
        return $this->getPersistenceHandler()->userHandler();
    }

    protected function getValidUser()
    {
        $user = new Persistence\User();
        $user->id = 42;
        $user->login = 'kore';
        $user->email = 'kore@example.org';
        $user->passwordHash = '1234567890';
        $user->hashAlgorithm = 2;
        $user->isEnabled = true;
        $user->maxLogin = 23;

        return $user;
    }

    public function testCreateUser()
    {
        $handler = $this->getUserHandler();
        return $handler->create( $this->getValidUser() );
    }

    /**
     * @expectedException \RuntimeException
     * @depends testCreateUser
     */
    public function testCreateDuplicateUser()
    {
        $handler = $this->getUserHandler();

        $handler->create( $this->getValidUser() );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInsertIncompleteUser()
    {
        $handler = $this->getUserHandler();

        $user = new Persistence\User();
        $user->id = 42;

        $handler->create( $user );
    }

    /**
     * @depends testCreateUser
     */
    public function testLoadUser( $user )
    {
        $handler = $this->getUserHandler();
        $this->assertEquals(
            $user,
            $handler->load( $user->id )
        );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadUnknownUser()
    {
        $handler = $this->getUserHandler();

        $handler->load( 1337 );
    }

    /**
     * @depends testCreateUser
     */
    public function testLoadUserByLogin( $user )
    {
        $handler = $this->getUserHandler();
        $users = $handler->loadByLogin( $user->login );
        $this->assertEquals(
            $user,
            $users[0]
        );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testLoadUserByEmailNotFound()
    {
        $handler = $this->getUserHandler();
        $handler->loadByLogin( 'unknown' );
    }

    /**
     * @depends testCreateUser
     */
    public function testLoadUserByEmail( $user )
    {
        $handler = $this->getUserHandler();
        $users = $handler->loadByLogin( $user->email, true );
        $this->assertEquals(
            $user,
            $users[0]
        );
    }

    /**
     * @depends testCreateUser
     */
    public function testUpdateUser( $user )
    {
        $handler = $this->getUserHandler();
        $user->login = 'new_login';
        $handler->update( $user );

        return $user;
    }

    /**
     * @depends testUpdateUser
     */
    public function testLoadUpdatedUserByLogin( $user )
    {
        $handler = $this->getUserHandler();
        $users = $handler->loadByLogin( $user->login );
        $this->assertEquals(
            $user,
            $users[0]
        );
    }

    /**
     * @depends testCreateUser
     */
    public function testUpdateUserSettings( $user )
    {
        $handler = $this->getUserHandler();

        $user->maxLogin = 42;
        $handler->update( $user );
    }

    /**
     * @depends testCreateUser
     */
    public function testCreateAndDeleteUser( $user )
    {
        $handler = $this->getUserHandler();
        $handler->delete( $user->id );
    }

    /**
     * @expectedException \eZ\Publish\API\Repository\Exceptions\NotFoundException
     */
    public function testDeleteNonExistingUser()
    {
        $handler = $this->getUserHandler();
        $handler->delete( 1337 );
    }

    public function testSilentlyUpdateNotExistingUser()
    {
        $handler = $this->getUserHandler();
        $handler->update( $this->getValidUser() );
    }

    public function testCreateNewRoleWithoutPolicies()
    {
        $handler = $this->getUserHandler();

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';

        return $handler->createRole( $role );
    }

    /**
     * @depends testCreateNewRoleWithoutPolicies
     */
    public function testLoadRole( $role )
    {
        $handler = $this->getUserHandler();

        $this->assertEquals(
            $role,
            $handler->loadRole( $role->id )
        );
    }

    /**
     * @depends testCreateNewRoleWithoutPolicies
     */
    public function testLoadRoles( $role )
    {
        $handler = $this->getUserHandler();

        $this->assertEquals(
            array( $role ),
            $handler->loadRoles()
        );
    }

    /**
     * @depends testCreateNewRoleWithoutPolicies
     */
    public function testUpdateRole( $role )
    {
        $handler = $this->getUserHandler();

        $update = new Persistence\User\RoleUpdateStruct();
        $update->id = $role->id;
        $update->identifier = 'changed';

        $handler->updateRole( $update );
    }

    /**
     * @depends testCreateNewRoleWithoutPolicies
     */
    public function testDeleteRole( $role )
    {
        $handler = $this->getUserHandler();

        $handler->deleteRole( $role->id );
    }

    /**
     * @depends testCreateNewRoleWithoutPolicies
     */
    public function testLoadRoleWithGroups()
    {
        $handler = $this->getUserHandler();

        $this->markTestIncomplete( "This test requires creation of content objects first." );

        $role = new Persistence\User\Role();
        $role->identifier = 'GroupTest';

        $role = $handler->createRole( $role );

        $handler->assignRole( 23, $role->id );
        $handler->assignRole( 42, $role->id );

        $loaded = $handler->loadRole( $role->id );
        $this->assertEquals(
            array( 23, 42 ),
            $loaded->groupIds
        );

        $handler->deleteRole( $role->id );
    }

    /**
     * @depends testCreateNewRoleWithoutPolicies
     */
    public function testLoadRoleWithPolicies( $role )
    {
        $handler = $this->getUserHandler();

        $role = new Persistence\User\Role();
        $role->identifier = 'PoliciesTest';

        $role = $handler->createRole( $role );

        $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';

        $handler->addPolicy( $role->id, $policy );

        $loaded = $handler->loadRole( $role->id );
        $this->assertPropertiesCorrect(
            array(
                'module' => 'foo',
                'function' => 'bar',
                'limitations' => '*',
            ),
            $loaded->policies[0]
        );

        $handler->deleteRole( $role->id );
    }

    /**
     * @depends testLoadRoleWithPolicies
     */
    public function testLoadRoleWithPoliciesAndGroups( $role )
    {
        $handler = $this->getUserHandler();

        $this->markTestIncomplete( "This test requires creation of content objects first." );

        $role = new Persistence\User\Role();
        $role->identifier = 'PoliciesGroupTest';

        $role = $handler->createRole( $role );

        $handler->assignRole( 23, $role->id );
        $handler->assignRole( 42, $role->id );

        $loaded = $handler->loadRole( $role->id );
        $this->assertPropertiesCorrect(
            array(
                'module' => 'foo',
                'function' => 'bar',
                'limitations' => '*',
            ),
            $loaded->policies[0]
        );

        $this->assertEquals(
            array( 23, 42 ),
            $loaded->groupIds
        );

        $handler->deleteRole( $role->id );
    }

    public function testLoadRoleWithPoliciyLimitations()
    {
        $handler = $this->getUserHandler();

        $role = new Persistence\User\Role();
        $role->identifier = 'TestLimitations';

        $role = $handler->createRole( $role );

        $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';
        $policy->limitations = array(
            'Subtree' => array( '/1', '/1/2' ),
            'Foo' => array( 'Bar' ),
        );

        $handler->addPolicy( $role->id, $policy );

        $loaded = $handler->loadRole( $role->id );
        $this->assertPropertiesCorrect(
            array(
                'module' => 'foo',
                'function' => 'bar',
                'limitations' => array(
                    'Subtree' => array( '/1', '/1/2' ),
                    'Foo' => array( 'Bar' ),
                ),
            ),
            $loaded->policies[0]
        );

        $handler->deleteRole( $role->id );
    }

    public function testAddPolicyToRole()
    {
        $handler = $this->getUserHandler();

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';
        $handler->createRole( $role );

        $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';

        $handler->addPolicy( $role->id, $policy );

        $loaded = $handler->loadRole( $role->id );
        $this->assertPropertiesCorrect(
            array(
                'module' => 'foo',
                'function' => 'bar',
                'limitations' => '*',
            ),
            $loaded->policies[0]
        );

        $handler->deleteRole( $role->id );
    }

    public function testAddPolicyLimitations()
    {
        $handler = $this->getUserHandler();

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';
        $handler->createRole( $role );

        $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';
        $policy->limitations = array(
            'Subtree' => array( '/1', '/1/2' ),
            'Foo' => array( 'Bar' ),
        );

        $handler->addPolicy( $role->id, $policy );

        $loaded = $handler->loadRole( $role->id );
        $this->assertPropertiesCorrect(
            array(
                'module' => 'foo',
                'function' => 'bar',
                'limitations' => array(
                    'Subtree' => array( '/1', '/1/2' ),
                    'Foo' => array( 'Bar' ),
                )
            ),
            $loaded->policies[0]
        );

        $handler->deleteRole( $role->id );
    }

    public function testRemovePolicy()
    {
        $handler = $this->getUserHandler();

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';
        $role->policies[] = $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';
        $policy->limitations = array(
            'Subtree' => array( '/1', '/1/2' ),
            'Foo' => array( 'Bar' ),
        );
        $handler->createRole( $role );

        $handler->removePolicy( $role->id, $role->policies[0]->id );

        $loaded = $handler->loadRole( $role->id );
        $this->assertSame(
            array(),
            $loaded->policies
        );

        $handler->deleteRole( $role->id );
    }

    public function testUpdatePolicies()
    {
        $handler = $this->getUserHandler();

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';
        $role->policies[] = $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';
        $policy->limitations = array(
            'Subtree' => array( '/1', '/1/2' ),
            'Foo' => array( 'Bar' ),
        );
        $handler->createRole( $role );

        $policy = $role->policies[0];
        $policy->limitations = array(
            'new' => array( 'something' ),
        );

        $handler->updatePolicy( $policy );

        $loaded = $handler->loadRole( $role->id );
        $this->assertPropertiesCorrect(
            array(
                'module' => 'foo',
                'function' => 'bar',
                'limitations' => array(
                    'new' => array( 'something' ),
                )
            ),
            $loaded->policies[0]
        );

        $handler->deleteRole( $role->id );
    }

    public function testAddRoleToUser()
    {
        $handler = $this->getUserHandler();

        $this->markTestIncomplete( "This test requires creation of content objects first." );

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';

        $handler->createRole( $role );

        $user = $this->getValidUser();
        $handler->assignRole( $user->id, $role->id );

        // @TODO: Assert role assignment
    }

    public function testAddRoleToUserWithLimitation()
    {
        $handler = $this->getUserHandler();

        $this->markTestIncomplete( "This test requires creation of content objects first." );

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';
        $role->policies[] = $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';

        $handler->createRole( $role );

        $user = $this->getValidUser();
        $handler->assignRole(
            $user->id,
            $role->id,
            array(
                'Subtree' => array( '/1' ),
            )
        );

        // @TODO: Fetch policies for user
    }

    public function testRemoveUserRoleAssociation()
    {
        $handler = $this->getUserHandler();

        $this->markTestIncomplete( "This test requires creation of content objects first." );

        $role = new Persistence\User\Role();
        $role->identifier = 'Test';
        $role->policies[] = $policy = new Persistence\User\Policy();
        $policy->module = 'foo';
        $policy->function = 'bar';

        $handler->createRole( $role );

        $handler->create( $user = $this->getValidUser() );

        $handler->assignRole(
            $user->id,
            $role->id,
            array(
                'Subtree' => array( '/1', '/1/2' ),
                'Foo' => array( 'Bar' ),
            )
        );

        $handler->unAssignRole( $user->id, $role->id );

        // @TODO: Fetch policies for user
    }
}
