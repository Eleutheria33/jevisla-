<?php

/**
 * This file is part of the Symfony 3.4.15 -coding-standard (phpcs standard).
 *
 * PHP version 7.1.9
 *
 * @category Class
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/djoos/Symfony2-coding-standard
 */

namespace Jevisla\AdministrationBundle\Controller;  

use Jevisla\AlerteBundle\Entity\Category;
use Jevisla\AlerteBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This file is part of the Symfony 3.4.15 -coding-standard (phpcs standard).
 *
 * PHP version 7.1.9
 *
 * @category Class
 *
 * @author   Patrick Maina <demosthene33@gmail.com>
 * @license  http://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/djoos/Symfony2-coding-standard
 */
class AdministrationController extends Controller
{
    /**
     * Mise à jour de la catégorie.
     *
     * @param int     $id      identifiant
     *                         catégorie
     * @param Request $request formulaire
     *
     * @return Response Description
     */
    public function updateCategoryAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('JevislaAlerteBundle:Category')
            ->find($id);

        if (null === $category) {
            throw new NotFoundHttpException('Cette catégorie '.$id." n'existe pas.");
        }

        // Ici encore, il faudra mettre la gestion du formulaire
        $form = $this->get('form.factory')
            ->create(CategoryType::class, $category);

        if ($request->isMethod('POST')
            && $form->handleRequest($request)->isValid()
        ) {
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'Categorie bien modifiée.');

            return $this->redirectToRoute(
                'jevisla_administration_home', array(
                'id' => $category->getId(),
                )
            );
        }

        return $this->render(
            'JevislaAdministrationBundle:Administration:updateCategory.html.twig',
            array(
                    'category' => $category,
                    'form' => $form->createView(),
            )
        );
    }

    /**
     * Création d'une catégorie.
     *
     * @param Request $request formulaire
     *
     * @return Response Description
     */
    public function addCategoryAction(Request $request)
    {
        $category = new Category();
        $form = $this->get('form.factory')
            ->create(CategoryType::class, $category);

        if ($request->isMethod('POST')
            && $form->handleRequest($request)->isValid()
        ) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('notice', 'Categorie bien enregistrée.');

            return $this->redirectToRoute(
                'jevisla_administration_home', array(
                'id' => $category->getId(),
                )
            );
        }

        return $this->render(
            'JevislaAdministrationBundle:Administration:addCategory.html.twig',
            array(
            'form' => $form->createView(),
            )
        );
    }

    /**
     * Affichage d'une catégorie.
     *
     * @param int $id Identifiant
     *                catégorie
     *
     * @return Response Description
     */
    public function viewCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        // Pour récupérer une seule catégory, on utilise la méthode find($id)
        $category = $em->getRepository('JevislaAlerteBundle:Category')->find($id);

        // $category est donc une instance de OC\PlatformBundle\Entity\Advert
        // ou null si l'id $id n'existe pas, d'où ce if :
        if (null === $category) {
            throw new NotFoundHttpException('La catégorie'.$id." n'existe pas.");
        }

        return $this->render(
            'JevislaAdministrationBundle:Administration:viewCategory.html.twig',
            array(
                    'category' => $category,
            )
        );
    }

    /**
     * Affichage des catégories catégorie.
     *
     * @param int $page Page des catégories en
     *                  cours
     *
     * @return Response Description
     */
    public function indexCategoryAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
        }

        // Ici je fixe le nombre d'annonces par page à 3
        // Mais bien sûr il faudrait utiliser un paramètre,
        // et y accéder via $this->container->getParameter('nb_per_page')
        $nbPerPage = 4;

        // On récupère notre objet Paginator
        $listCategories = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaAlerteBundle:Category')
            ->getCategories($page, $nbPerPage);
        // dump($listCategories);
        // On calcule le nombre total de pages grâce au count($listAlertes)
        // qui retourne le nombre total d'annonces
        $nbPages = ceil(count($listCategories) / $nbPerPage);
        $nbCategories = count($listCategories);

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException('La page '.$page." n'existe pas.");
        }

        // On donne toutes les informations nécessaires à la vue
        return $this->render(
            'JevislaAdministrationBundle:Administration:listeCategories.html.twig',
            array(
                    'listCategories' => $listCategories,
                    'nbPages' => $nbPages,
                    'page' => $page,
                    'nbCategories' => $nbCategories,
                    'nbPerPage' => $nbPerPage,
            )
        );
    }

    /**
     * Suppression d'une catégorie.
     *
     * @param Request $request formulaire
     * @param int     $id      identifiant
     *                         catégorie
     *
     * @return Response Description
     */
    public function deleteCategoryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('JevislaAlerteBundle:Category')->find($id);
        $tableauCatName = $em->getRepository('JevislaAlerteBundle:Category')
            ->getAllIdCategory();

        if (null === $category) {
            throw new NotFoundHttpException('La catégorie '.$id." n'existe pas.");
        }
        // test si la catégorie est utilisée par une alerte
        // création du tableau de test
        foreach ($tableauCatName as $tabname) {
            $allname[] = $tabname['name'];
        }
        $testCatAlerte = $em->getRepository('JevislaAlerteBundle:Alerte')
             ->getAlerteWithCategory($allname);
        foreach ($testCatAlerte as $catId) {
            $allid[] = $catId['id'];
        }
        if (in_array($id, $allid)) {
            $request->getSession()
                ->getFlashBag()
                ->add(
                    'info',
                    'Catégorie ne peut être supprimée : catégorie liée.'
                );

            return $this->redirectToRoute('jevisla_administration_home');
        }

        // On crée un formulaire vide, qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression d'annonce contre cette faille
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST')
            && $form->handleRequest($request)->isValid()
        ) {
            $em->remove($category);
            $em->flush();

            $request->getSession()
                ->getFlashBag()
                ->add('info', 'La catégorie a été supprimée.');

            return $this->redirectToRoute('jevisla_administration_home');
        }

        return $this->render(
            'JevislaAdministrationBundle:Administration:deleteCategory.html.twig',
            array(
            'category' => $category,
            'form' => $form->createView(),
            )
        );
    }
}
