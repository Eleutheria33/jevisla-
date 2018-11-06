<?php

namespace Jevisla\ForumsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JevislaForumsBundle extends Bundle
{
    public function getParent()
    {
        return 'DForumBundle';
    }
}
