<?php
/*
 * This file is part of the FulgurioUserBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FulgurioUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
