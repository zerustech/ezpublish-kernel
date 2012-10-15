<?php

namespace eZ\Bundle\EzPublishRestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle,
    Symfony\Component\DependencyInjection\ContainerBuilder;

class EzPublishRestBundle extends Bundle
{
    public function build( ContainerBuilder $container )
    {
        parent::build( $container );

        $securityExtension = $container->getExtension( 'security' );
        $securityExtension->addSecurityListenerFactory( new DependencyInjection\TestSecurityFactory() );
    }
}
