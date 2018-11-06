<?php

/**
 * This file is part of the Symfony 3.4.15 -coding-standard (phpcs standard).
 *
 * PHP version 7.1.9
 *
 * @category PHP
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/djoos/Symfony2-coding-standard
 */

namespace Jevisla\EvtsCulturelsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jevisla\EvtsCulturelsBundle\Entity\Commune;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * This file is part of the Symfony 3.4.15 -coding-standard (phpcs standard).
 *
 * PHP version 7.1.9
 *
 * @category PHP
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/djoos/Symfony2-coding-standard
 */
class EvtsCulturelsController extends Controller
{
    /**
     * Affichage des événements culturels de la ville de l'user.
     *
     * @param int     $page    numero de page
     * @param Request $request formulaire de recherche
     *
     * @return Response Description
     */    
    public function indexAction($page, Request $request)
    {
        $user = $this->getUser();
        $limit = 100;
        // supression & réinitialisation des recherches manuelles
        // utilisation de $page à 1000 comme astuce de réinitialisation
        if (1000 == $page) {
            $this->get('session')->remove('radius');
            $this->get('session')->remove('Commune');
            $this->get('session')->remove('departement');
            $this->get('session')->remove('dateD');
            $this->get('session')->remove('dateF');
            $page = 1;
        }
        // nombre d'annonce affichées par page max
        $nbPerPage = 15;
        // on initialise
        $offset = 0;
        // on définit l'interval à un mois (période par défaut)
        $date = new \DateTime();
        $dateE = $date;
        $dateStart = $date->format('d/m/Y');
        $dateEnd = $dateE->add(new \DateInterval('P1M'));
        $dateFin = $dateEnd->format('d/m/Y');
        // bifurcation du choix ici ou ailleurs (événements culturels)
        if ($request->isMethod('POST') || $this->get('session')->get('Commune')) {
            if ($request->isMethod('POST')) {
                $params = $request->request->get('deptCom');
                $paras = explode(' : ', $params);
                $radius = $request->request->get('distance') * 1000; //passage de kilomètres à mètres
                $dateStart = $request->request->get('dateD');
                $dateFin = $request->request->get('dateF');

                $this->get('session')->set('radius', $radius);
                $this->get('session')->set('Commune', $paras[0]);
                $this->get('session')->set('departement', $paras[1]);
                $this->get('session')->set('dateD', $dateStart);
                $this->get('session')->set('dateF', $dateFin);
            }
            $com = $this->get('session')->get('Commune');
            $dep = $this->get('session')->get('departement');
            $radius = $this->get('session')->get('radius');
            $dateStart = $this->get('session')->get('dateD');
            $dateFin = $this->get('session')->get('dateF');
            $communeSelect = $this->getDoctrine()
                ->getManager()
                ->getRepository('JevislaEvtsCulturelsBundle:Commune')
                ->getCommuneSelect($com, $dep);
            // inversion liées au fichier (partie de base de données inversée en lat/long
            if ($communeSelect[0]->getLatitude() < 20) {
                $lat = $communeSelect[0]->getLongitude();
                $lng = $communeSelect[0]->getLatitude();
            } else {
                $lat = $communeSelect[0]->getLatitude();
                $lng = $communeSelect[0]->getLongitude();
            }
            $offset = 0;
        } else {
            $lat = $user->getLatitude();
            $lng = $user->getLongitude();
            $offset = 0;
            $radius = 3000;
        }
        // calcul du nombre d'enregistrements et de pages
        try {
            $countJson = file_get_contents(
            'https://api.openagenda.com/v1/events?key=d24fe4830c6944dc85be1381c3df911d&lat='.$lat.'&lng='.$lng.'&radius='.$radius.
            '&when='.$dateStart.'-'.$dateFin.'&offset='.$offset.'&limit='.$limit.')'
            );
            $countObj = json_decode($countJson);
            $nbPages = ceil(count($countObj->data) / $nbPerPage);
        } catch (Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        // Si la page n'existe pas, on boucle sur les pages existantes
        if ($page > $nbPages) {
            $page = 1;
            $firstLine = 0;
        }
        if ($page < 1) {
            $page = $nbPages;
            $firstLine = 0;
        } else {
            $firstLine = ($page - 1) * 10; // calcul de la série à récupérer
        }
        try {
            $json = file_get_contents(
            'https://api.openagenda.com/v1/events?key=d24fe4830c6944dc85be1381c3df911d&lat='.$lat.'&lng='.$lng.'&radius='.$radius.
            '&when='.$dateStart.'-'.$dateFin.'&offset='.$firstLine.'&limit='.$nbPerPage.')'
            );
            $obj = json_decode($json);
        } catch (Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        
        // On calcule le nombre total de pages grâce au count($listAlertes) qui retourne le nombre total d'annonces
        //$nbEvtsCulturels = count($obj);
        return $this->render(
            'JevislaEvtsCulturelsBundle:EvtsCulturels:index.html.twig',
            array(
            'evtsCulturels' => $obj,
            'nbPages' => $nbPages,
            'page' => $page,
            'nbPerPage' => $nbPerPage,
            )
        );
    }
    
    /**
     * Recherche d'une commune
     *
     * @return Response Description
     */ 
    public function chargementCommuneAction()
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQuery('SELECT DISTINCT(a.codeDept) From JevislaEvtsCulturelsBundle:Commune a');
        $results = $qb->getResult();
        try {
            $departments = file_get_contents('https://geo.api.gouv.fr/departements?fields=nom,code');
            $departements = json_decode($departments);
            foreach ($departements as $department) {
                for ($i = 0; $i < count($results); ++$i) {
                    if ($department->code == $results[$i][1]) {
                        break;
                    }
                    // si pas sortie de la boucle nous intégrons le département
                    if ($i == (count($results) - 1)) {
                        $dept = $department->code;
                        $communes = file_get_contents('https://geo.api.gouv.fr/departements/'.$dept.'/communes?fields=nom,code,codesPostaux,centre,surface,codeDepartement,departement,codeRegion,region,population&format=json&geometry=centre');
                        $communeAll = json_decode($communes);
                        foreach ($communeAll as $commune) {
                            $com = new commune();
                            $com->setNom($commune->nom);
                            $com->setCode($commune->code);
                            $com->setCodePostal($commune->codesPostaux[0]);
                            $com->setlatitude($commune->centre->coordinates[1]);
                            $com->setlongitude($commune->centre->coordinates[0]);
                            $com->setCodeDept($commune->departement->code);
                            $com->setNomDept($commune->departement->nom);
                            $em->persist($com);
                            $em->flush();
                        }
                    }
                }
            }
        
        } catch (Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }

        return $this->render('JevislaEvtsCulturelsBundle:EvtsCulturels:index.html.twig');
    }

    /**
     * Recherche d'une commune
     *
     * @param Request $communes formulaire de recherche commune
     *
     * @return Response Description
     */     
    public function searchCommunesAction(Request $communes)
    {
        $commune = $communes->get('commune');
        // On récupère notre objet Paginator
        $listCommunes = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaEvtsCulturelsBundle:Commune')
            ->getCommune($commune, 15);
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $serializer = new Serializer(array($normalizer), array($encoder));
        $data = $serializer->serialize($listCommunes, 'json');
        $response = new Response($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
