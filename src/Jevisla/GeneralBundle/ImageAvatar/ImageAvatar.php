<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\GeneralBundle\ImageAvatar;

use  Doctrine\ORM\EntityManagerInterface;
use  Symfony\Component\DependencyInjection\ContainerInterface;

class ImageAvatar
{
    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * @return Avatar
     */
    public function getImage($id)
    {
        $ficheGoogle = $this->em->getRepository('JevislaMapBundle:FicheUserGoogleMap')->find($id);
        if ($ficheGoogle) {
            // récupération de l'image, on utilise la méthode find($id)
            $avatar = $this->em->getRepository('JevislaMapBundle:Avatar')->find($ficheGoogle->getAvatar());
            if ($avatar) {
                return $avatar;
            }
        }
    }
}
