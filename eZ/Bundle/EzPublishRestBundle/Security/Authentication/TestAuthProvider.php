<?php
/**
 * File containing the TestAuthProvider class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishRestBundle\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use eZ\Publish\Core\MVC\Symfony\Security\User;

class TestAuthProvider implements AuthenticationProviderInterface
{
    private $repository;

    public function __construct( UserProviderInterface $userProvider, \Closure $repository )
    {
        $this->repository = $repository;
    }

    public function authenticate( TokenInterface $token )
    {
        $repository = $this->repository;
        $repository = $repository();

        try
        {
            $apiUser = $repository->getUserService()->loadUser( $token->userID );
            $repository->setCurrentUser( $apiUser );

            $token->setAuthenticated( true );
            return $token;
        }
        catch ( \Exception $e )
        {
            throw new AuthenticationException( 'The test authentication failed: ' . $e->getMessage(), null, 0, $e );
        }
    }

    public function supports( TokenInterface $token )
    {
       return $token instanceof TestAuthToken;
    }
}

