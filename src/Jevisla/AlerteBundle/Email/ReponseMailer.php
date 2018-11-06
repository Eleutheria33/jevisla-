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

namespace Jevisla\AlerteBundle\Email;

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
class ReponseMailer
{
    /**
     * Service Mailer.
     *
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Instanciation de la classe avec un objet ReponseMailer.
     *
     * @param Swift_Mailer $mailer Description
     */
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Envoi d'un mail que pour les entities Reponse.
     *
     * @param Reponse $reponse Description
     *
     * @return mail Description
     */
    public function sendNewNotification(Reponse $reponse)
    {
        $message = new \Swift_Message(
            'Nouvelle réponse',
            'Vous avez reçu une nouvelle réponse.'
        );
        $message
            ->addTo($reponse->getAlerte()->getUser()->getEmail())
            ->addFrom('administration@jevisla.prope-me.com');

        $this->mailer->send($message);
    }
}
