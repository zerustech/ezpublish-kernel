<?php
/**
 * File containing the TestAuthListener class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishRestBundle\Security\Authentication;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;

class TestAuthListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;

    public function __construct( SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager )
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
    }

    public function handle( GetResponseEvent $event )
    {
        $token   = new TestAuthToken();
        $request = $event->getRequest();
        $token->userID = $request->server->get( 'HTTP_X_TEST_USER' );

        $this->securityContext->setToken(
            $this->authenticationManager->authenticate( $token )
        );
    }
}

