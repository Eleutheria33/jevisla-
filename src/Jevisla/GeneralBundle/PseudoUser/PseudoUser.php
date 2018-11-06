<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\GeneralBundle\PseudoUser;

use  Doctrine\ORM\EntityManagerInterface;
use  Symfony\Component\DependencyInjection\ContainerInterface;

class PseudoUser
{
    protected $em;
    protected $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    /**
     * @return Pseudo
     */
    public function getPseudo($id)
    {
        $user = $this->em->getRepository('JevislaUserBundle:User')->find($id);
        if ($user) {
            // récupération du pseudo, on utilise la méthode find($id)
            $pseudo = $user->getPseudo();
            if ($pseudo) {
                return $pseudo;
            }
        }
    }
}
