<?php

// src/Jevisla/AlerteBundle/Controller/AlerteController.php

namespace Jevisla\MapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jevisla\UserBundle\Entity\User;
use Jevisla\MapBundle\Entity\FicheUserGoogleMap;
use Jevisla\MapBundle\Form\FicheUserGoogleMapType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class MapController extends Controller
{
    public function indexAction()
    {
        // On donne toutes les informations nécessaires à la vue
        $user = $this->getUser();
        // affichage de l'image de l'user
        if (is_object($user)) {
            return $this->render(
                'JevislaMapBundle:Map:index.html.twig',
                array(
                'user' => $user,
                )
            );
        } else {
            return $this->render('JevislaMapBundle:Map:index.html.twig');
        }
    }

    public function recupCoordonneesAction(Request $coordonnees)
    {
        $idUser = $coordonnees->get('id');

        if ($coordonnees->isXMLHttpRequest()) {
            // si $coords est null récupération coordonnées sinon intégration des nuvelles coordonnées
            $coords = $coordonnees->get('zone');
            if (null !== $coords) {
                $latNE = $coords[0];
                $longNE = $coords[1];
                $latSW = $coords[2];
                $longSW = $coords[3];
            }
            $em = $this->getDoctrine()->getManager();
            // nous récupérons l'utilisateur en ligne, on utilise la méthode find($id)
            $user = $em->getRepository('JevislaUserBundle:User')->find($idUser);
            // on teste son existence
            if (null === $user) {
                throw new NotFoundHttpException("L'utilisateur d'id ".$idUser." n'existe pas.");
            }
            // on valorise les nouvelles coordonnées de zone de contact sinon on renvoie les coordonnées
            if (null !== $coords) {
                $user->setLatitudeNE($latNE);
                $user->setLatitudeSW($latSW);
                $user->setLongitudeNE($longNE);
                $user->setLongitudeSW($longSW);
                // on persiste et on signe
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return new response('ok');
            } else {
                // modification de setCircularReferenceLimit car l'objet User est intégré à l'objet Alerte
                $encoder = new JsonEncoder();
                $normalizer = new ObjectNormalizer();
                $normalizer->setCircularReferenceLimit(0);
                $normalizer->setCircularReferenceHandler(
                    function ($object) {
                        return $object->getId();
                    }
                );
                $serializer = new Serializer(array($normalizer), array($encoder));
                $data = $serializer->serialize($user, 'json');
                $response = new Response($data);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }
    }

    public function recupVoisinsAction(Request $zone)
    {
        if ($zone->isXMLHttpRequest()) {
            $TabVoisins = $zone->get('zone');
            $latNE = $TabVoisins[0];
            $longNE = $TabVoisins[1];
            $latSW = $TabVoisins[2];
            $longSW = $TabVoisins[3];
            $latitude = $TabVoisins[4];
            $longitude = $TabVoisins[5];
            $listVoisins = $this->getDoctrine()
                ->getManager()
                ->getRepository('JevislaUserBundle:User')
                ->getUsersVoisins($latNE, $latSW, $longNE, $longSW, $latitude, $longitude);
            foreach ($listVoisins as $listVoisin) {
                $voisin = $listVoisin;
            }
            // modification de setCircularReferenceLimit car l'objet User est intégré à l'objet Alerte
            $encoder = new JsonEncoder();
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceLimit(0);

            $normalizer->setCircularReferenceHandler(
                function ($object) {
                    return $object->getId();
                }
            );
            $serializer = new Serializer(array($normalizer), array($encoder));
            $data = $serializer->serialize($listVoisins, 'json');
            $response = new Response($data);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
    }

    public function formFicheUserGMAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        // affichage de la fiche de l'utilisateur
        if (is_object($user)) {
            if (null === $user->getFicheGoogle()) {
                $ficheUserGM = new FicheUserGoogleMap();
            } else {
                $ficheUserGM = $em->getRepository('JevislaMapBundle:FicheUserGoogleMap')->find($user->getFicheGoogle());
            }
            $form = $this->get('form.factory')->create(FicheUserGoogleMapType::class, $ficheUserGM);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $ficheUserGM->setIdUser($user->getId());
                (null === $user->getFicheGoogle()) ? $em->persist($ficheUserGM) : 'rien';
                $em->flush();
                if (null === $user->getFicheGoogle()) {
                    $userGM = $em->getRepository('JevislaUserBundle:User')->find($user->getId());
                    $userGM->setFicheGoogle($ficheUserGM);
                    $em->flush();
                }
                $request->getSession()->getFlashBag()->add('notice', 'Fiche GM sauvegardée.');

                return $this->redirectToRoute('jevisla_map_homepage');
            }

            return $this->render(
                'JevislaMapBundle:Map:formFicheUserGM.html.twig',
                array(
                'form' => $form->createView(),
                )
            );
        } else {
            return;
        }
    }
}
