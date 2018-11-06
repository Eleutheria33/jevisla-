<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\GeneralBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GeneralController extends Controller
{
    public function indexAction()
    {
        return $this->render('JevislaGeneralBundle:General:index.html.twig');
    }

    public function contactAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email', TextType::class)
            ->add('content', TextareaType::class)
            ->add('envoyer', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dump($form->getData('content'));
            dump($request);
            $request->getSession()
                    ->getFlashBag()
                    ->add('answer', 'Votre message a été envoyé avec succès !!');

            return $this->redirect($this->generateUrl('jevisla_general_homepage').'#contact');
        }

        return $this->render(
            'JevislaGeneralBundle:General:contact.html.twig',
            array(
            'form' => $form->createView(),
            )
        );
    }

    public function sendContactAction(Request $request)
    {
        $message = $request->get('message');
        $email = $request->get('email');
        $from = $this->container->getParameter('mailer_user');
        // réception du message dans messagerie 1&1
        $receptionMessage = \Swift_Message::newInstance()
            ->setSubject('contact-jevisla')
            ->setFrom($from)
            ->setTo($from)
            ->setContentType('text/html')
            ->setBody($message);

        $this->get('mailer')->send($receptionMessage);

        // réponse au message de la messagerie 1&1
        $sendMessage = \Swift_Message::newInstance()
            ->setSubject('contact-jevisla')
            ->setFrom($from)
            ->setTo($email)
            ->setContentType('text/html')
            ->setBody(
                '<body><html><div> Merci de votre intérêt, nous avons bien reçu votre message et nous vous adresserons une réponse dès que possible.'
                          .' <br> Bien à vous. '
                          .' <br> L\'équipe JevisLa. '
                .' </div></html></body>'
            );

        $this->get('mailer')->send($sendMessage);

        $reponse['reponse'] = 'yes';

        return new response(json_encode($reponse));
    }
}
