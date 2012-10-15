<?php
/**
 * File containing the REST security Factory class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishRestBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\DefinitionDecorator,
    Symfony\Component\Config\Definition\Builder\NodeDefinition,
    Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

/**
 * Basic auth based authentication provider, working with eZ Publish repository
 */
class TestSecurityFactory implements SecurityFactoryInterface
{
    public function create( ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint )
    {
        return array(
            'ezpublish.security.authentication_provider.test_auth',
            $id,
            $defaultEntryPoint
        );
    }

    public function getPosition()
    {
        return 'http';
    }

    public function getKey()
    {
        return 'ezpublish_test_auth';
    }

    public function addConfiguration( NodeDefinition $node )
    {
        return;
    }
}
