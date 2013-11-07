<?php

/**
 * File containing the Role class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\SPI\Persistence\User;

use eZ\Publish\SPI\Persistence\MultiLanguageValueBase;

/**
 */
class Role extends MultiLanguageValueBase
{
    /**
     * ID of the user rule.
     *
     * @var mixed
     */
    public $id;

    /**
     * Policies associated with the role.
     *
     * @var \eZ\Publish\SPI\Persistence\User\Policy[]
     */
    public $policies = array();
}
