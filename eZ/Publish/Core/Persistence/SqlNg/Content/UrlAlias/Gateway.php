<?php
/**
 * File containing the UrlAlias Gateway class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\SqlNg\Content\UrlAlias;

/**
 * UrlAlias Gateway
 */
abstract class Gateway
{
    const AUTO = 1;

    const HISTORY = 2;

    const CUSTOM = 3;
    // @TODO: Add abstract methods
}
