<?php
/**
 * File containing the REST security Factory class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishRestBundle\DependencyInjection;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\AbstractFactory,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\DefinitionDecorator,
    Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Basic auth based authentication provider, working with eZ Publish repository
 */
class TestSecurityFactory extends AbstractFactory
{
    const AUTHENTICATION_PROVIDER_ID = 'ezpublish.security.authentication_provider.test_auth';
    const AUTHENTICATION_LISTENER_ID = 'ezpublish.security.firewall_listener.test_auth';

    /**
     * Subclasses must return the id of a service which implements the
     * AuthenticationProviderInterface.
     *
     * @param ContainerBuilder $container
     * @param string           $id             The unique id of the firewall
     * @param array            $config         The options array for this listener
     * @param string           $userProviderId The id of the user provider
     *
     * @return string never null, the id of the authentication provider
     */
    protected function createAuthProvider( ContainerBuilder $container, $id, $config, $userProviderId )
    {
        $providerId = self::AUTHENTICATION_PROVIDER_ID . ".$id";
        $container
            ->setDefinition( $providerId, new DefinitionDecorator( self::AUTHENTICATION_PROVIDER_ID ) )
            ->replaceArgument( 0, new Reference( $userProviderId ) )
            ->addArgument( $id )
        ;

        return $providerId;
    }

    /**
     * Subclasses must return the id of the listener template.
     *
     * @return string
     */
    protected function getListenerId()
    {
        return self::AUTHENTICATION_LISTENER_ID;
    }

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'ezpublish_test_auth';
    }
}
