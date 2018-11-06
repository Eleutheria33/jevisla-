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

namespace Jevisla\AlerteBundle\Controller;

use Jevisla\AlerteBundle\Entity\Alerte;
use Jevisla\AlerteBundle\Entity\Reponse;
use Jevisla\AlerteBundle\Form\reponseAlerteType;
use Jevisla\AlerteBundle\Form\AlerteEditType;
use Jevisla\AlerteBundle\Form\AlerteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
class AlerteController extends Controller
{
    /**
     * Affichage des alertes par page.
     *
     * @param Request $request formulaire
     * @param int     $page    numero de page
     *
     * @return Response Description
     */
    public function indexAction(Request $request, $page)
    {
        $date = new \DateTime();
        if ($page < 1) {
            $page = 1;
        }
        //récupration de la route en cours pour gestion author
        $currentRoute = $request->attributes->get('_route');
        // Ici je fixe le nombre d'annonces par page
        $nbPerPage = $this->container->getParameter('nbrAlertesPerPage');
        // On récupère notre objet Paginator
        $listAlertes = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaAlerteBundle:Alerte')
            ->getAlertes($page, $nbPerPage, $date);
        //nombre total de pages $listAlertes retourne le nombre total d'annonces
        $nbPages = ceil(count($listAlertes) / $nbPerPage);
        if ($nbPages >= 1) {
            $nbAlertes = count($listAlertes);
            // Si la page n'existe pas, on retourne une 404
            if ($page > $nbPages) {
                $page = 1;
            }
            // On donne toutes les informations nécessaires à la vue
            return $this->render(
                'JevislaAlerteBundle:Alerte:index.html.twig',
                array(
                        'listAlertes' => $listAlertes,
                        'nbPages' => $nbPages,
                        'page' => $page,
                        'nbAlertes' => $nbAlertes,
                        'nbPerPage' => $nbPerPage,
                        'routeEnCours' => $currentRoute,
                )
            );
        } else {
            // On donne toutes les informations nécessaires à la vue
            return $this->render(
                'JevislaAlerteBundle:Alerte:index.html.twig',
                array(
                        'listAlertes' => 'Aucune annonce',
                        'nbPages' => 0,
                        'page' => 1,
                        'nbAlertes' => 0,
                        'nbPerPage' => 0,
                        'routeEnCours' => $currentRoute,
                )
            );
        }
    }

    /**
     * Retourne la liste des alertes d'un auteur.
     *
     * @param Request $request  formulaire
     * @param int     $page     numero de  page
     * @param int     $authorId Description
     *
     * @return Response Description
     */
    public function authorAction(Request $request, $page, $authorId)
    {
        $date = new \DateTime();
        if ($page < 1) {
            $page = 1;
        }
        //récupration de la route en cours pour gestion author
        $currentRoute = $request->attributes->get('_route');
        // Ici je fixe le nombre d'annonces par page à 3
        $nbPerPage = $this->container->getParameter('nbrAlertesPerPage');
        // On récupère notre objet Paginator
        $listAlertes = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaAlerteBundle:Alerte')
            ->getAlertesAuthor($page, $nbPerPage, $authorId, $date);
        //nombre total de pages $listAlertes retourne le nombre total d'annonces
        $nbPages = ceil(count($listAlertes) / $nbPerPage);
        if ($nbPages >= 1) {
            $nbAlertes = count($listAlertes);
            // Si la page n'existe pas, on retourne une 404
            if ($page > $nbPages) {
                $page = 1;
            }
            // On donne toutes les informations nécessaires à la vue
            return $this->render(
                'JevislaAlerteBundle:Alerte:index.html.twig',
                array(
                        'listAlertes' => $listAlertes,
                        'nbPages' => $nbPages,
                        'page' => $page,
                        'nbAlertes' => $nbAlertes,
                        'nbPerPage' => $nbPerPage,
                        'routeEnCours' => $currentRoute,
                )
            );
        } else {
            // On donne toutes les informations nécessaires à la vue
            return $this->render(
                'JevislaAlerteBundle:Alerte:index.html.twig',
                array(
                        'listAlertes' => 'Aucune annonce',
                        'nbPages' => 0,
                        'page' => 1,
                        'nbAlertes' => 0,
                        'nbPerPage' => 0,
                        'routeEnCours' => $currentRoute,
                )
            );
        }
    }

    /**
     * Affichage d'une alerte et ses réponses.
     *
     * @param int $id identifiant user
     *
     * @return Response retourne vue d'une alerte & ses réponses
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        // Pour récupérer une seule annonce, on utilise la méthode find($id)
        $alerte = $em->getRepository('JevislaAlerteBundle:Alerte')->find($id);
        if (null === $alerte) {
            throw new NotFoundHttpException("L'alerte ".$id." n'existe pas.");
        }
        // Récupération de la liste des réponses de l'alerte
        $listResponses = $em
            ->getRepository('JevislaAlerteBundle:Reponse')
            ->xReponseAlerte($alerte);
        // Récupération des AlerteSkill de l'alerte
        $listAlerteSkills = $em
            ->getRepository('JevislaAlerteBundle:AlerteSkill')
            ->findBy(array('alerte' => $alerte));

        return $this->render(
            'JevislaAlerteBundle:Alerte:view.html.twig',
            array(
                    'alerte' => $alerte,
                    'listResponses' => $listResponses,
                    'listAlerteSkills' => $listAlerteSkills,
            )
        );
    }

    /**
     * Affichage minimal d'une alerte et ses réponses.
     *
     * @param int $id identifiant user
     *
     * @return Response affichage alerte complète
     */
    public function miniatureAction($id)
    {
        $date = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        // Pour récupérer une seule annonce, on utilise la méthode find($id)
        $alerte = $em
            ->getRepository('JevislaAlerteBundle:Alerte')
            ->getAlertesSingle($date, $id)[0];
        if (null === $alerte) {
            throw new NotFoundHttpException("L'alerte ".$id." n'existe pas.");
        }
        // on tronque les textes trop longs appel du service de troncage
        $textrq = $this->container->get('jevisla_general.textTraitement');
        $alerte
            ->setContent($textrq->getTextTronqué($alerte->getContent(), 50, 20));
        // Récupération de la liste des réponses à l'annonce
        $listResponses = $em
            ->getRepository('JevislaAlerteBundle:Reponse')
            ->findBy(array('alerte' => $alerte));
        // Récupération des AlerteSkill de l'annonce
        $listAlerteSkills = $em
            ->getRepository('JevislaAlerteBundle:AlerteSkill')
            ->findBy(array('alerte' => $alerte));

        return $this->render(
            'JevislaAlerteBundle:Alerte:miniature.html.twig',
            array(
            'alerte' => $alerte,
            'listResponses' => $listResponses,
            'listAlerteSkills' => $listAlerteSkills,
            )
        );
    }

    /**
     * Création d'une alerte.
     *
     * @param Request $request formulaire
     *
     * @return Response Description
     */
    public function addAction(Request $request)
    {
        $alerte = new Alerte();
        $form = $this->get('form.factory')->create(AlerteType::class, $alerte);
        $user = $this->getUser();
        if ($request->isMethod('POST')
            && $form->handleRequest($request)->isValid()
        ) {
            $alerte->setUser($user);
            $alerte->setAuthor($user->getPseudo());
            $alerte->setAuthorId($user->getId());
            $em = $this->getDoctrine()->getManager();
            $em->persist($alerte);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'Alerte bien enregistrée.');

            return $this->redirectToRoute(
                'jevisla_alerte_view', array(
                'id' => $alerte->getId(),
                )
            );
        }

        return $this->render(
            'JevislaAlerteBundle:Alerte:add.html.twig',
            array(
            'form' => $form->createView(),
            )
        );
    }

    /**
     * Modification d'une alerte.
     *
     * @param int     $id      identifiant user
     * @param Request $request récupération formulaire
     *
     * @return Response Description
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $alerte = $em->getRepository('JevislaAlerteBundle:Alerte')->find($id);
        if (null === $alerte) {
            throw new NotFoundHttpException("L'alerte".$id." n'existe pas.");
        }
        // la gestion du formulaire
        $form = $this->get('form.factory')
            ->create(AlerteEditType::class, $alerte);
        $imageAlerte = $alerte->getImageAlerte();
        if ($request->isMethod('POST')
            && $form->handleRequest($request)->isValid()
        ) {
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'Alerte modifiée.');

            return $this->redirectToRoute(
                'jevisla_alerte_view', array(
                'id' => $alerte->getId(),
                )
            );
        }
        // test si l'alerte a une image sinon affichage d'une
        if ($imageAlerte) {
            return $this->render(
                'JevislaAlerteBundle:Alerte:edit.html.twig',
                array(
                        'alerte' => $alerte,
                        'imageAl' => $imageAlerte,
                        'form' => $form->createView(),
                )
            );
        } else {
            return $this->render(
                'JevislaAlerteBundle:Alerte:edit.html.twig',
                array(
                        'alerte' => $alerte,
                        'form' => $form->createView(),
                )
            );
        }
    }

    /**
     *  Création d'une réponse à une alerte.
     *
     * @param int     $id      identifiant user
     * @param Request $request récupération formulaire
     *
     * @return Response Description
     */
    public function repondreAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if ($id) {
            $alerte = $em
                ->getRepository('JevislaAlerteBundle:Alerte')
                ->find($id);
        }
        if (null === $alerte) {
            throw new NotFoundHttpException("L'alerte".$id." n'existe pas.");
        }
        $reponse = new Reponse();
        // Ici encore, il faudra mettre la gestion du formulaire
        $form = $this->get('form.factory')
            ->create(reponseAlerteType::class, $reponse);
        if ($request->isMethod('POST')
            && $form->handleRequest($request)->isValid()
        ) {
            $reponse->setAuthor($user->getPseudo());
            $reponse->setAuthorId($user->getId());
            $alerte->addReponse($reponse);
            $em->persist($reponse);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'réponse enregistrée.');

            return $this->redirectToRoute(
                'jevisla_alerte_view', array(
                'id' => $alerte->getId(),
                )
            );
        }
        // test si l'alerte a une image sinon affichage d'une
        return $this->render(
            'JevislaAlerteBundle:Alerte:repondre.html.twig',
            array(
                   'form' => $form->createView(),
            )
        );
    }

    /**
     * Supression d'une alerte.
     *
     * @param Request $request récupération formulaire
     * @param int     $id      identifiant user
     *
     * @return Response Description
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $alerte = $em->getRepository('JevislaAlerteBundle:Alerte')->find($id);
        if (null === $alerte) {
            throw new NotFoundHttpException("L'annonce ".$id." n'existe pas.");
        }
        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();
        if ($request->isMethod('POST')
            && $form->handleRequest($request)->isValid()
        ) {
            $em->remove($alerte);
            $em->flush();
            $request->getSession()
                ->getFlashBag()
                ->add('info', "L'alerte a bien été supprimée.");

            return $this->redirectToRoute('jevisla_alerte_home');
        }

        return $this->render(
            'JevislaAlerteBundle:Alerte:delete.html.twig',
            array(
            'alerte' => $alerte,
            'form' => $form->createView(),
            )
        );
    }

    /**
     * Sélection des alertes par catégorie.
     *
     * @param int $limit nombre d'alertes maximales
     *
     * @return Response Description
     */
    public function menuAction($limit)
    {
        $date = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        // récupération de toutes les catégories pour le menu side
        $allCategories = $em
            ->getRepository('JevislaAlerteBundle:Category')
            ->findAll();
        foreach ($allCategories as $categorie) {
            $cat = $categorie->getId();
            $listAlertes = $em
                ->getRepository('JevislaAlerteBundle:Alerte')
                ->getAlerteWithCategorie($cat, $date);
            $alertesCat = '';
            foreach ($listAlertes as $alertesCat) {
                $listAlertCat[$categorie->getName()][] = $alertesCat;
            }
        }

        return $this->render(
            'JevislaAlerteBundle:Alerte:menu.html.twig',
            array(
                        'listAlertCat' => $listAlertCat,
            )
        );
    }

    /**
     * Purge toutes les alertes (non opérationnelles).
     *
     * @param int     $days    nombre de jours de purge
     * @param Request $request formulaire
     *
     * @return Response Description
     */
    public function purgeAction($days, Request $request)
    {
        // on récupère le service
        $purgeAlerte = $this->container->get('jevisla_alerte.purger.alerte');
        //  on lance la purge des alertes sans réponse
        $purgeAlerte->purge($days);
        //return new Response('Purge effectuée') ;
        $this->get('session')->getFlashBag()->add('info', 'Purge effectuée');

        return $this->redirectToRoute('jevisla_alerte_home');
    }

    /**
     * Explication sur validator utilisé automatiquement par les formulaire.
     *
     * @return Response Description
     */
    public function testAction()
    {
        $alerte = new Alerte();
        $alerte->setDate(new \Datetime()); //« date » OK
        $alerte->setTitle('abc');          // incorrect :- de 10 caractères
        //$advert->setContent('blabla');   // incorrect : on ne le définit pas
        $alerte->setAuthor('A');           //incorrect : moins de 2 caractères

        // On récupère le service validator
        $validator = $this->get('validator');

        // On déclenche la validation sur notre object
        $listErrors = $validator->validate($alerte);

        // Si $listErrors n'est pas vide, on affiche les erreurs
        if (count($listErrors) > 0) {
            // $listErrors est un objet, __toString liste joliement les erreurs
            return new Response((string) $listErrors);
        } else {
            return new Response("L'alerte est valide !");
        }
    }

    /**
     * Affichage des dernières alertes en page d'accueil.
     *
     * @param int $page page d'affichage
     *
     * @return Response Description
     */
    public function afficheAccueilAction($page)
    {
        $date = new \DateTime();
        if ($page < 1) {
            $page = 1;
        }
        // Ici je fixe le nombre d'annonces par page
        $nbPerPage = $this->container->getParameter('nbrAlertesAccueil');
        // On récupère notre objet Paginator
        $listAlertes = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaAlerteBundle:Alerte')
            ->getAlertesAccueil($page, $nbPerPage, $date);
        // nombre total de pages qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listAlertes) / $nbPerPage);
        if ($nbPages >= 1) {
            $nbAlertes = count($listAlertes);
            // Si la page n'existe pas, on retourne une 404
            if ($page > $nbPages) {
                $page = 1;
            }
            // On donne toutes les informations nécessaires à la vue
            return $this->render(
                'JevislaAlerteBundle:Alerte:afficheAccueil.html.twig',
                array(
                        'listAlertes' => $listAlertes,
                        'nbPages' => $nbPages,
                        'page' => $page,
                        'nbAlertes' => $nbAlertes,
                        'nbPerPage' => $nbPerPage,
                )
            );
        } else {
            // On donne toutes les informations nécessaires à la vue
            return $this->render(
                'JevislaAlerteBundle:Alerte:afficheAccueil.html.twig',
                array(
                        'listAlertes' => 'Aucune annonce',
                        'nbPages' => 0,
                        'page' => 1,
                        'nbAlertes' => 0,
                        'nbPerPage' => 0,
                )
            );
        }
    }

    /**
     * Affichage des dernières alertes en page d'accueil.
     *
     * @param int $id identifiant de l'user
     *
     * @return Response Description
     */
    public function alertesAccueilAction($id)
    {
        $date = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        // Pour récupérer une seule annonce, on utilise la méthode find($id)
        $alerte = $em->getRepository('JevislaAlerteBundle:Alerte')
            ->getAlertesSingle($date, $id)[0];
        if (null === $alerte) {
            throw new NotFoundHttpException("L'alerte ".$id." n'existe pas.");
        }
        // on tronque les textes trop longs appel du service de troncage
        $textrq = $this->container->get('jevisla_general.textTraitement');
        // Récupération de la liste des candidatures de l'annonce

        return $this->render(
            'JevislaAlerteBundle:Alerte:alertesAccueil.html.twig',
            array(
            'alerte' => $alerte,
            )
        );
    }

    /**
     * Affichage des dernières réponses auteur en page d'accueil.
     *
     * @param int $author identifiant de l'auteur
     *
     * @return Response Description
     */
    public function alertesReponsesAccueilAction($author)
    {
        // On récupère notre objet Paginator
        $listAlertesReponses = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaAlerteBundle:Alerte')
            ->getAlertesReponses($author);
        // On donne toutes les informations nécessaires à la vue
        return $this->render(
            'JevislaAlerteBundle:Alerte:alertesReponsesAccueil.html.twig',
            array(
                    'listAlertesReponses' => $listAlertesReponses,
            )
        );
    }
}
