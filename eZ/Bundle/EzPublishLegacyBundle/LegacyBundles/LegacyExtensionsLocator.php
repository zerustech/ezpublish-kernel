<?php
/**
 * File containing the LegacyExtensionsLocator class.
 *
 * @copyright Copyright (C) 2014 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace eZ\Bundle\EzPublishLegacyBundle\LegacyBundles;

use DirectoryIterator;

class LegacyExtensionsLocator implements LegacyExtensionsLocatorInterface
{
    public function locate( $bundlePath )
    {
        $bundlePath = rtrim( $bundlePath, '/\\' );

        $return = array();

        if ( !is_dir( $bundlePath ) )
        {
            return $return;
        }

        /** @var $item DirectoryIterator */
        foreach ( new DirectoryIterator( "$bundlePath/ezpublish_legacy/" ) as $item )
        {
            if ( !$item->isDir() )
            {
                continue;
            }

            if ( file_exists( $item->getPathname() . '/extension.xml' ) )
            {
                $return[] = $item->getPathname();
            }
        }
        return $return;
    }
}
