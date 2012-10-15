<?php
/**
 * File containing the MysqlTestResetListener class.
 *
 * @copyright Copyright (C) 2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishRestBundle\EventListener;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use eZ\Publish\Core\REST\Server\Request as RESTRequest;

/**
 * This class listens, as a service, for the kernel.view event, triggered when a controller method
 * didn't return a Response object.
 *
 * It converts the RestValue / Value Object to a Response using Visitors
 */
class MysqlTestResetListener implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \eZ\Publish\Core\REST\Server\Request
     */
    private $request;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \eZ\Publish\Core\REST\Server\Request $request
     */
    public function __construct( ContainerInterface $container, RESTRequest $request )
    {
        $this->container = $container;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest'
        );
    }

    /**
     * Clear MySQL database, if a new session started
     *
     * @param GetResponseEvent $event
     * @return void
     */
    public function onKernelRequest( GetResponseEvent $event )
    {
        try
        {
            $sessionId = $this->request->testSession;
            $cacheDir  = $this->container->getParameter( 'kernel.cache_dir' );

            if ( !is_file( $fileName = $cacheDir . '/sess-' . $sessionId ) )
            {
                touch( $fileName );
                $this->resetDatabase();
            }
        }
        catch ( \InvalidArgumentException $e )
        {
            // No test session.
        }
    }

    /**
     * Reset the database
     *
     * @return void
     */
    protected function resetDatabase()
    {
        $repository = $this->container->get( 'ezpublish.api.repository' );

        $persistenceProperty = new \ReflectionProperty( get_class( $repository ), 'persistenceHandler' );
        $persistenceProperty->setAccessible( true );
        $persistence = $persistenceProperty->getValue( $repository );

        $dbHandlerProperty = new \ReflectionProperty( get_class( $persistence ), 'dbHandler' );
        $dbHandlerProperty->setAccessible( true );
        $dbHandler = $dbHandlerProperty->getValue( $persistence );

        $setupFactory = new \eZ\Publish\API\Repository\Tests\SetupFactory\Legacy();
        $setupFactory->initializeSchema( $dbHandler );
        $setupFactory->insertData( $dbHandler );
    }
}
