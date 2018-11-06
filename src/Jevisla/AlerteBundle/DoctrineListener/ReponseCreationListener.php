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

namespace Jevisla\AlerteBundle\DoctrineListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Jevisla\AlerteBundle\Email\ReponseMailer;
use Jevisla\AlerteBundle\Entity\Reponse;

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
class ReponseCreationListener
{
    /**
     * Chargé d'envoyer un email.
     *
     * @var ReponseMailer Mail réponse
     */
    private $reponseMailer;

    /**
     * Instanciation de la classe avec un objet ReponseMailer.
     *
     * @param Mailer $reponseMailer Description
     */
    public function __construct(ReponseMailer $reponseMailer)
    {
        $this->reponseMailer = $reponseMailer;
    }

    /**
     * Envoi d'un mail que pour les entities Reponse.
     *
     * @param objet $args Description
     *
     * @return mail Description
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        // On ne veut envoyer un email que pour les entités Application
        if (!$entity instanceof Reponse) {
            return;
        }

        $this->reponseMailer->sendNewNotification($entity);
    }
}
