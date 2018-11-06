<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\GeneralBundle\ImageUser;

use  Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImageUser
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
    public function getImage($id)
    {
        $user = $this->em->getRepository('JevislaUserBundle:User')->find($id);
        if ($user) {
            // récupération de l'image, on utilise la méthode find($id)
            $image = $this->em->getRepository('JevislaUserBundle:Image')->find($user->getImage());
            if ($image) {
                return $image;
            }
        }
    }
}
