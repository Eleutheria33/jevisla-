<?php

namespace Jevisla\MessagerieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Jevisla\MessagerieBundle\Entity\Conversation;
use Jevisla\MessagerieBundle\Entity\Messages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MessagerieController extends Controller
{
    public function indexAction($id)
    {
        // récupération de la fiche user du voisin
        $em = $this->getDoctrine()->getManager();
        $idUse = $this->getUser()->getId();

        $idVois = (int) $id;
        $voisin = $em->getRepository('JevislaUserBundle:User')->getMessageVoisins($id);
        $idUser = $em->getRepository('JevislaUserBundle:User')->getMessageVoisins($this->getUser()->getId()); //->getId();

        if ($voisin) {
            $idVoisinFg = $voisin[0]->getficheGoogle() ? $voisin[0]->getficheGoogle()->getId() : '';
            if ($voisin[0]->getficheGoogle()) {
                $pseudo2 = $voisin[0]->getficheGoogle()->getPseudo1() ? $voisin[0]->getficheGoogle()->getPseudo1() : $voisin[0]->getPseudo();
            } else {
                $pseudo2 = $voisin[0]->getPseudo();
            }
        }

        if ($idUser) {
            $userIdFG = $idUser[0]->getficheGoogle() ? $idUser[0]->getficheGoogle()->getId() : '';
            if ($idUser[0]->getficheGoogle()) {
                $pseudo1 = $idUser[0]->getficheGoogle()->getPseudo1() ? $idUser[0]->getficheGoogle()->getPseudo1() : $idUser[0]->getPseudo();
            } else {
                $pseudo1 = $idUser[0]->getPseudo();
            }
        }

        // mise à jour du flag "lu" pour les messages affichés à l'utilisateur
        $conversation = $this->retrieveConverse($this->getUser()->getId(), $id);
        if ($conversation) {
            $this->getDoctrine()
                ->getManager()
                ->getRepository('JevislaMessagerieBundle:Messages')
                ->updateLusMessages($id, $conversation->getId(), $em);
        } else {
            $conversation = 'nulle';
        }

        return $this->render(
            'JevislaMessagerieBundle:Messagerie:index.html.twig',
            array(
                    'pseudo1' => $pseudo1,
                    'pseudo2' => $pseudo2,
                    'user' => $userIdFG,
                    'voisin' => $idVoisinFg,
                    'id' => $idUse,
                    'idVoisin' => $idVois,
                    'conversation' => $conversation,
            )
        );
    }

    public function messageAction(Request $message)
    {
        $em = $this->getDoctrine()->getManager();
        $lastMessage = $message->get('message');
        $idOne = $message->get('idOne');
        $idTwo = $message->get('idTwo');

        $conversation = $this->retrieveConverse($idOne, $idTwo);

        if ($conversation) {
            // ajout du message à la conversation $idOne est toujours le créateur du message donc idUser de Messages
            // affectation du numéro chronologique du message
            $newMessage = $this->newMessage($idOne, $lastMessage, $conversation, $em);
            $conversation->addMessage($newMessage);
        } else {
            // création d'une conversation
            $this->createConverse($idOne, $idTwo, $em);
            $conversation = $this->retrieveConverse($idOne, $idTwo);
            $newMessage = $this->newMessage($idOne, $lastMessage, $conversation, $em);
            $conversation->addMessage($newMessage);
        }
        $em->flush(); // --> enregistrement du message et de la conversation
        $reponselastMessage['donnee'] = $lastMessage;
        $dateMessage = new \DateTime();
        $reponselastMessage['date'] = $dateMessage->format('d-m-Y à H:i:s');
        $reponselastMessage['date_time'] = $dateMessage->format('Y-m-d H:i:s'); // future date_time (format bdd) en data-date_time
        return new response(json_encode($reponselastMessage));
    }

    public function lastMessageAction(Request $message)
    {
        $em = $this->getDoctrine()->getManager();
        $datetime = $message->get('datetime');
        $converse = $message->get('converse');
        $idTwo = $message->get('idTwo');

        $conversation = $this->retrieveLastConverse($converse, $idTwo, $datetime);
        if ($conversation) {
        } else {
            $conversation = 'Aucune discussion';
        }

        return new response(json_encode($conversation));
    }

    public function newMessage($idOne, $message, $conversation, $em)
    {
        // création du message
        $newMessage = new Messages();
        $newMessage->setIdUser($idOne);
        $newMessage->setIdConversation($conversation->getId());
        $newMessage->setMessage($message);
        $newMessage->setConversation($conversation);
        $number = $conversation->getNbMessages() + 1;
        $newMessage->setNumber($number);
        $em->persist($newMessage);

        return $newMessage;
    }

    public function retrieveConverse($idOne, $idTwo)
    {
        $conversation = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaMessagerieBundle:Conversation')
            ->getConversationVoisin($idOne, $idTwo);
        if ($conversation) {
            return $conversation[0];
        } else {
            return '';
        }
    }

    public function retrieveLastConverse($converse, $idTwo, $datetime)
    {
        //$dateT = new \DateTime($datetime);
        $lastMessages = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaMessagerieBundle:Messages')
            ->getLastMessage($idTwo, $converse, $datetime);
        if ($lastMessages) {
            return $lastMessages;
        }

        return '';
    }

    public function createConverse($idOne, $idTwo, $em)
    {
        $conversation = new Conversation();
        $conversation->setIdOne($idOne);
        $conversation->setIdTwo($idTwo);
        $em->persist($conversation);
        $em->flush();
    }

    public function retrieveMessagesNonLusAction(Request $message)
    {
        $idUser = $message->get('idUser');
        $idVoisin = $message->get('idVoisin');
        $conversation = $this->retrieveConverse($idUser, $idVoisin);
        if ($conversation) {
            $countMessagesNonLus = $this->getDoctrine()
                ->getManager()
                ->getRepository('JevislaMessagerieBundle:Messages')
                ->getNonLusMessages($idVoisin, $conversation->getId());
        } else {
            $countMessagesNonLus = '0';
        }

        return new response(json_encode($countMessagesNonLus));
    }

    public function messagesNonLusAccueilAction()
    {
        // récupération de la fiche user du voisin
        $idUser = $this->getUser()->getId();

        // mise à jour du flag "lu" pour les messages affichés à l'utilisateur

        $listMessagesNonLusAccueil = $this->getDoctrine()
            ->getManager()
            ->getRepository('JevislaMessagerieBundle:Conversation')
            ->getMessagesNonLusAccueil($idUser);
        if ($listMessagesNonLusAccueil) {
            return $this->render(
                'JevislaMessagerieBundle:Messagerie:messagesNonLusAccueil.html.twig',
                array(
                            'listMessagesNonLusAccueil' => $listMessagesNonLusAccueil,
                )
            );
        } else {
            $reponseNegative = ' aucun message non lu ';

            return $this->render(
                'JevislaMessagerieBundle:Messagerie:messagesNonLusAccueil.html.twig',
                array(
                        'reponseNegative' => $reponseNegative,
                )
            );
        }
    }
}
