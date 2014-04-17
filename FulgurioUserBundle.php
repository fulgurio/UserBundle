<?php

namespace Fulgurio\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FulgurioUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
