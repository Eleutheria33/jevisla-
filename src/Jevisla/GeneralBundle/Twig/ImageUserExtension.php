<?php

// src/Jevisla/GeneralBundle/Twig/AntispamExtension.php

namespace Jevisla\GeneralBundle\Twig;

use Jevisla\GeneralBundle\ImageUser\ImageUser;

class ImageUserExtension extends \Twig_Extension
{
    /**
     * @var JevislaImageUser
     */
    private $ImageUser;

    public function __construct(ImageUser $ImageUser)
    {
        $this->ImageUser = $ImageUser;
    }

    public function checkIfArgumentIsId($id)
    {
        return $this->ImageUser->getImage($id);
    }

    // Twig va exécuter cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getObjetImageUser', array($this, 'checkIfArgumentIsId')),
        );
    }

    // La méthode getName() identifie l'extension Twig, elle est obligatoire
    public function getName()
    {
        return 'ImageUser';
    }
}
