<?php

// src/Jevisla/GeneralBundle/Twig/AntispamExtension.php

namespace Jevisla\GeneralBundle\Twig;

use Jevisla\GeneralBundle\RegexDate\RegexDate;

class RegexDateExtension extends \Twig_Extension
{
    /**
     * @var JevislaImageNav
     */
    private $RegexDate;

    public function __construct(RegexDate $RegexDate)
    {
        $this->RegexDate = $RegexDate;
    }

    public function checkIfArgumentIsDate($date, $order)
    {
        return $this->RegexDate->getNewDate($date, $order);
    }

    // Twig va exécuter cette méthode pour savoir quelle(s) fonction(s) ajoute notre service
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getNewDate', array($this, 'checkIfArgumentIsDate')),
        );
    }

    // La méthode getName() identifie votre extension Twig, elle est obligatoire
    public function getName()
    {
        return 'RegexDate';
    }
}
