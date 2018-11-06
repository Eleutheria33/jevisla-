<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jevisla\UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Controller managing the user profile.
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends Controller
{
    /**
     * Show the user.
     */
    public function showAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        if (is_object($user)) {
            if ($user->getImage()) {
                // pavé de surcharge dû à l'affichage de l'image (récupérée directement dans la base "Image")
                $em = $this->getDoctrine()->getManager();
                // récupération de l'image, on utilise la méthode find($id)
                $image = $em->getRepository('JevislaUserBundle:Image')->find($user->getImage());

                // ou null si l'id $id n'existe pas, d'où ce if :
                if (null === $image) {
                    throw new NotFoundHttpException('Aucune image');
                }

                return $this->render(
                    '@FOSUser/Profile/show.html.twig',
                    array(
                    'user' => $user,
                    'image' => $image,
                    )
                );
            } else {
                return $this->render(
                    '@FOSUser/Profile/show.html.twig',
                    array(
                    'user' => $user,
                    )
                );
            }
        }
    }

    /**
     * Edit the user.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        if ($user->getImage()) {
            // pavé de surcharge dû à l'affichage de l'image (récupérée directement dans la base "Image")
            $em = $this->getDoctrine()->getManager();
            // récupération de l'image, on utilise la méthode find($id)
            $image = $em->getRepository('JevislaUserBundle:Image')->find($user->getImage());
            // $advert est donc une instance de OC\PlatformBundle\Entity\Advert
            // ou null si l'id $id n'existe pas, d'où ce if :
            if (null === $image) {
                throw new NotFoundHttpException('Aucune image');
            }
        }
        /**
         * @var EventDispatcherInterface
         */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /**
         * @var FactoryInterface
         */
        $formFactory = $this->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UserManagerInterface
             */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('jevisla_general_homepage');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        if ($user->getImage()) {
            return $this->render(
                '@FOSUser/Profile/edit.html.twig',
                array(
                'form' => $form->createView(),
                'image' => $image,
                )
            );
        } else {
            return $this->render(
                '@FOSUser/Profile/edit.html.twig',
                array(
                'form' => $form->createView(),
                )
            );
        }
    }
}
