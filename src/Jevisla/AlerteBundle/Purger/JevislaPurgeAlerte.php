<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\AlerteBundle\Purger\Alerte;

class JevislaPurgeAlerte
{
    private $em;
    private $repository;

    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->em = $em;
        $this->repository = $this->em->getRepository('JevislaAlerteBundle:Alerte');
    }

    public function purge($days)
    {
        $alerteToDelete = $this->repository->getAlerteWithoutReponses($days);

        if (null === $advertToDelete) {
            throw new NotFoundHttpException('Aucune alerte à supprimer.');
        }

        // On boucle sur les annonces (alerte) pour les supprimer
        foreach ($alerteToDelete as $alerte) {
            $this->em->remove($alerte);
        }
        $this->em->flush();
    }
}
