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

namespace Jevisla\AlerteBundle\Antispam;

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
class JevislaAntispam
{
    private $mailer;
    private $locale;
    private $minLength;

    /**
     * Vérifie si le texte est un spam ou non.
     *
     * @param Swift_Mailer $mailer    Service de mail
     * @param string       $locale    Description
     * @param int          $minLength Longueur
     *                                vérifiée
     *
     * @return bool
     */
    public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
    {
        $this->mailer = $mailer;
        $this->locale = $locale;
        $this->minLength = (int) $minLength;
    }

    /**
     * Vérifie si le texte est un spam ou non.
     *
     * @param string $text Description
     *
     * @return bool Retourne vrai si spam
     */
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;
    }
}
