<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\GeneralBundle\Localisation;

use  Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Localisation
{
    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * @return carte
     */
    public function getLocalisation($id)
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
