<?php

/**
 * File containing the eZ\Publish\API\Repository\Values\User\RoleCreateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
namespace eZ\Publish\API\Repository\Values\User;

use eZ\Publish\API\Repository\Values\MultiLanguageCreateStructBase;

/**
 * This class is used to create a new role.
 */
abstract class RoleCreateStruct extends MultiLanguageCreateStructBase
{
    /**
     * Returns policies associated with the role.
     *
     * @return \eZ\Publish\API\Repository\Values\User\PolicyCreateStruct[]
     */
    abstract public function getPolicies();

    /**
     * Adds a policy to this role.
     *
     * @param \eZ\Publish\API\Repository\Values\User\PolicyCreateStruct $policyCreateStruct
     */
    abstract public function addPolicy(PolicyCreateStruct $policyCreateStruct);
}
