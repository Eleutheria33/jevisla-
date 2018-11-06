<?php

// src/Jevisla/GeneralBundle/Twig/AntispamExtension.php

namespace Jevisla\GeneralBundle\Twig;

use Jevisla\GeneralBundle\ImageNav\ImageNav;

class ImageNavExtension extends \Twig_Extension
{
    /**
     * @var JevislaImageNav
     */
    private $ImageNav;

    public function __construct(ImageNav $ImageNav)
    {
        $this->ImageNav = $ImageNav;
    }

    public function checkIfArgumentIsId()
    {
        return $this->ImageNav->getImage();
    }

    // Twig va exécuter cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getObjetImage', array($this, 'checkIfArgumentIsId')),
        );
    }

    // La méthode getName() identifie votre extension Twig, elle est obligatoire
    public function getName()
    {
        return 'ImageNav';
    }
}
