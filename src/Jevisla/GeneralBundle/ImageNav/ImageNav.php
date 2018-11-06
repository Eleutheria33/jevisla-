<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\GeneralBundle\ImageNav;

use  Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImageNav
{
    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        // affichage de l'image de l'user
        if (is_object($user)) {
            if ($user->getImage()) {
                // récupération de l'image, on utilise la méthode find($id)
                $image = $this->em->getRepository('JevislaUserBundle:Image')->find($user->getImage());

                return $image;
            }
        }
    }
}
