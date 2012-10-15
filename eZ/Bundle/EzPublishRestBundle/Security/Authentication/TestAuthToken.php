<?php
/**
 * File containing the Token class.
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Bundle\EzPublishRestBundle\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class TestAuthToken extends AbstractToken
{
    public $userID = 10;

    public function getCredentials()
    {
        return $this->userId;
    }
}

