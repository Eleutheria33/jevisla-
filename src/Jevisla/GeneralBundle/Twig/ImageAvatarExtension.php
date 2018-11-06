<?php

// src/Jevisla/GeneralBundle/Twig/ImageAvatarExtension.php

namespace Jevisla\GeneralBundle\Twig;

use Jevisla\GeneralBundle\ImageAvatar\ImageAvatar;

class ImageAvatarExtension extends \Twig_Extension
{
    /**
     * @var JevislaImageAvatar
     */
    private $ImageAvatar;

    public function __construct(ImageAvatar $ImageAvatar)
    {
        $this->ImageAvatar = $ImageAvatar;
    }

    public function checkIfArgumentIsId($id)
    {
        return $this->ImageAvatar->getImage($id);
    }

    // Twig va exécuter cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getObjetImageAvatar', array($this, 'checkIfArgumentIsId')),
        );
    }

    // La méthode getName() identifie l'extension Twig, elle est obligatoire
    public function getName()
    {
        return 'ImageAvatar';
    }
}
