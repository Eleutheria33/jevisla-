<?php

namespace Jevisla\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JevislaUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
